<?php

namespace App\Console\Commands;

use App\Models\Entity;
use App\Models\Federation;
use App\Models\User;
use App\Traits\GitTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\ValidatorTrait;
use function Symfony\Component\String\b;

class DumbFromGit extends Command
{
    use GitTrait, ValidatorTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dumb-from-git';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    private function createFederations(): void
    {
        $cfgfiles = [];
        foreach (Storage::files() as $file) {
            if (preg_match('/^'.config('git.edugain_cfg').'$/', $file)) {
                continue;
            }

            if (preg_match('/\.cfg$/', $file)) {
                $cfgfiles[] = $file;
            }
        }
        $federations = Federation::select('cfgfile')->get()->pluck('cfgfile')->toArray();

        $unknown = [];
        foreach ($cfgfiles as $cfgfile) {
            if (in_array($cfgfile, $federations)) {
                continue;
            }

            $content = Storage::get($cfgfile);
            preg_match('/\[(.*)\]/', $content, $xml_id);
            preg_match('/filters\s*=\s*(.*)/', $content, $filters);
            preg_match('/name\s*=\s*(.*)/', $content, $xml_name);

            $unknown[$cfgfile]['cfgfile'] = $cfgfile;
            $unknown[$cfgfile]['xml_id'] = $xml_id[1];
            $unknown[$cfgfile]['filters'] = $filters[1];
            $unknown[$cfgfile]['xml_name'] = $xml_name[1];
        }

        foreach ($unknown as $fed)
        {
            DB::transaction(function () use ($fed) {
                $federation = Federation::create([
                    'name' => $fed['xml_id'],
                    'description' => $fed['xml_id'],
                    'tagfile' => preg_replace('/\.cfg$/', '.tag', $fed["cfgfile"]),
                    'cfgfile' => $fed["cfgfile"],
                    'xml_id' => $fed["xml_id"],
                    'xml_name' => $fed["xml_name"],
                    'filters' => $fed["filters"],
                    'explanation' => 'Imported from Git repository.',
                ]);

                $federation->approved = true;
                $federation->update();
            });
        }
    }

    private function hasChildElements(object $parent):bool
    {
        foreach ($parent->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                return true;
            }
        }

        return false;
    }
    private function deleteTag(object $tag):void
    {
        if($tag->parentNode)
        {
            $tag->parentNode->removeChild($tag);
        }
    }
    private function deleteNoChiledTag(object $tag):void
    {
        if (!$this->hasChildElements($tag)) {
            $this->deleteTag($tag);
        }
    }


    private function deleteCategoryTag(string $metadata): string
    {
        $dom = $this->createDOM($metadata);
        $xPath = $this->createXPath($dom);

        $values = config('categories');
        $xpathQueryParts = array_map(function($value) {
            return "text()='$value'";
        }, $values);



         $xpathQuery = '//saml:AttributeValue['. implode(' or ', $xpathQueryParts) .']';
         $tags = $xPath->query($xpathQuery);

        foreach ($tags as $tag) {
            $parent = $tag->parentNode;
            $grandParent = $parent->parentNode;
            $this->deleteTag($tag);

            $this->deleteNoChiledTag($parent);
            $this->deleteNoChiledTag($grandParent);
        }
        $dom->normalize();
        return $dom->saveXML();
    }

    private function createEntites(int $adminId): void
    {
        $xmlfiles = [];
        $tagfiles = [];
        $unknown = [];

        foreach (Storage::files() as $file) {
            if (preg_match('/\.xml$/', $file)) {
                $xmlfiles[] = $file;
            }

            if (preg_match('/\.tag$/', $file)) {
                if (preg_match('/^'.config('git.edugain_tag').'$/', $file)) {
                    continue;
                }

                $federation = Federation::whereTagfile($file)->first();
                if (!($federation === null && Storage::exists(preg_replace('/\.tag/', '.cfg', $file)))) {
                    $tagfiles[] = $file;
                }
            }
        }
        $tagfiles[] = config('git.edugain_tag');
        $entities = Entity::select('file')->get()->pluck('file')->toArray();

        foreach ($xmlfiles as $xmlfile) {
            if (in_array($xmlfile, $entities)) {
                continue;
            }
            $metadata = Storage::get($xmlfile);

            $xml_file= $this->deleteCategoryTag($metadata);

            $metadata = $this->parseMetadata($metadata);

            $entity = json_decode($metadata, true);



            $unknown[$xmlfile]['type'] = $entity['type'];
            $unknown[$xmlfile]['entityid'] = $entity['entityid'];
            $unknown[$xmlfile]['file'] = $xmlfile;
            $unknown[$xmlfile]['xml_file'] = $xml_file;
            $unknown[$xmlfile]['name_en'] = $entity['name_en'];
            $unknown[$xmlfile]['name_cs'] = $entity['name_cs'];
            $unknown[$xmlfile]['description_en'] = $entity['description_en'];
            $unknown[$xmlfile]['description_cs'] = $entity['description_cs'];
            $unknown[$xmlfile]['metadata'] = $entity['metadata'];


            foreach ($tagfiles as $tagfile) {
                $content = Storage::get($tagfile);
                $pattern = preg_quote($entity['entityid'], '/');
                $pattern = "/^$pattern\$/m";

                if (preg_match_all($pattern, $content)) {
                    if (strcmp($tagfile, config('git.edugain_tag')) === 0) {
                        $unknown[$xmlfile]['edugain'] = 1;
                        continue;
                    }

                    $federation = Federation::whereTagfile($tagfile)->first();
                    $unknown[$xmlfile]['federations'][] = $federation ?? null;
                }
            }
        }
        foreach ($unknown as $ent)
        {


            Db::transaction(function () use ($adminId, $ent) {
                $entity = Entity::create($ent);

                $entity->approved = true;
                $entity->update();
                foreach ($ent['federations'] as $fed)
                {
                    if(!empty($fed))
                    {
                        $entity->federations()->attach($fed,[
                            'requested_by' => $adminId,
                            'approved_by' => $adminId,
                            'approved' => true,
                            'explanation' => 'Imported from Git repository.',
                        ]);
                    }
                }

            });

        }
        $hfd = array_filter(preg_split("/\r\n|\r|\n/", Storage::get(config('git.hfd'))));
        foreach ($hfd as $entityid) {
            Entity::whereEntityid($entityid)->update(['hfd' => true]);
        }

        $rs = array_filter(preg_split("/\r\n|\r|\n/", Storage::get(config('git.ec_rs'))));
        foreach ($rs as $entityid) {
            Entity::whereEntityid($entityid)->update(['rs' => true]);
        }


    }



    /**
     * Execute the console command.
     */
    public function handle()
    {
      $firstAdminId = User::where('admin', 1)->first()->id;
        $git = $this->initializeGit();
        $this->createFederations();
        $this->createEntites($firstAdminId);

    }
}
