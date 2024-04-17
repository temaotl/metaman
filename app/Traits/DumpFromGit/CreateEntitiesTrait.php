<?php
namespace App\Traits\DumpFromGit;

use App\Models\Category;
use App\Models\Entity;
use App\Models\Federation;
use DOMNodeList;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait CreateEntitiesTrait{

   private $mdURI = 'urn:oasis:names:tc:SAML:2.0:metadata';
   private $mdattrURI = 'urn:oasis:names:tc:SAML:metadata:attribute';
   private $samlURI = 'urn:oasis:names:tc:SAML:2.0:assertion';


    private function hasChildElements(object $parent): bool
    {
        foreach ($parent->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE) {
                return true;
            }
        }

        return false;
    }

    private function deleteTag(object $tag): void
    {
        if ($tag->parentNode) {
            $tag->parentNode->removeChild($tag);
        }
    }

    private function deleteNoChilledTag(object $tag): void
    {
        if (!$this->hasChildElements($tag)) {
            $this->deleteTag($tag);
        }
    }

    private function DeleteAllTags(string $xpathQuery, \DOMXPath $xPath ) : void
    {
        $tags = $xPath->query($xpathQuery);

        foreach ($tags as $tag) {
            $parent = $tag->parentNode;
            $grandParent = $parent->parentNode;
            $this->deleteTag($tag);

            $this->deleteNoChilledTag($parent);
            $this->deleteNoChilledTag($grandParent);
        }
    }


    private function deleteCategories(\DOMXPath $xPath) :void
    {
        $values = config('categories');
        $xpathQueryParts = array_map(function ($value) {
            return "text()='$value'";
        }, $values);

        $xpathQuery = '//saml:AttributeValue[' . implode(' or ', $xpathQueryParts) . ']';
        $this->DeleteAllTags($xpathQuery,$xPath);
    }

    private function deleteResearchAndScholarship(\DOMXPath $xPath) : void
    {
        $value = "http://refeds.org/category/research-and-scholarship" ;
        $xpathQueryParts = "text()='$value'";

        $xpathQuery = '//saml:AttributeValue[' . $xpathQueryParts . ']';
        $this->DeleteAllTags($xpathQuery,$xPath);
    }

    private function deleteFromIdp( \DOMXPath $xPath ) : void
    {
        $this->deleteCategories($xPath);
    }

    private function deleteFromSP( \DOMXPath $xpath ) : void
    {
        $this->deleteResearchAndScholarship($xpath);


    }

    private function deleteRepublishRequest(\DOMXPath $xPath) : void
    {

        $xpathQuery = '//eduidmd:RepublishRequest';

        $tags = $xPath->query($xpathQuery);

        foreach ($tags as $tag) {
            $this->deleteTag($tag);
        }

    }


    private function deleteTags(string $metadata): string
    {
        $dom = $this->createDOM($metadata);
        $xPath = $this->createXPath($dom);

        // Make action for IDP
        if($this->isIDP($xPath))
        {
            $this->deleteFromIdp($xPath);
        }
        // Make  action for SP
        else
        {
            $this->deleteFromSP($xPath);
        }


        // Make action for Sp and Idp
        $this->deleteRepublishRequest($xPath);


        $dom->normalize();
        return $dom->saveXML();
    }

    private function updateXmlCategories(string $xml_document, int $category_id ) : string
    {
        $dom = $this->createDOM($xml_document);
        $xPath = $this->createXPath($dom);

        $rootTag = $xPath->query("//*[local-name()='EntityDescriptor']")->item(0);
        $entityExtensions = $xPath->query('//md:Extensions');
        if($entityExtensions->length === 0)
        {
            $namespaceURI = $dom->documentElement->lookupNamespaceURI('md');
            $entityExtensions = $dom->createElementNS('urn:oasis:names:tc:SAML:2.0:metadata', 'md:Extensions');
            $rootTag->appendChild($entityExtensions);
        }else {
            $entityExtensions = $entityExtensions->item(0);
        }
        $entityAttributes = $xPath->query('//mdattr:EntityAttributes');
        if ($entityAttributes->length === 0) {
            $entityAttributes = $dom->createElementNS('urn:oasis:names:tc:SAML:metadata:attribute', 'mdattr:EntityAttributes');
            $entityExtensions->appendChild($entityAttributes);
        } else {
            $entityAttributes = $entityAttributes->item(0);
        }

        $attribute = $xPath->query('//mdattr:EntityAttributes/saml:Attribute', $entityAttributes);
        if ($attribute->length === 0) {

            $attribute = $dom->createElementNS('urn:oasis:names:tc:SAML:2.0:assertion', 'saml:Attribute');

            $attribute->setAttribute('Name', 'http://macedir.org/entity-category');
            $attribute->setAttribute('NameFormat', 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri');

            $entityAttributes->appendChild($attribute);
        } else {
            $attribute = $attribute->item(0);
        }

        // Entity::whereId($entity->id)->update(['xml_file' => $xml_document]);
        $categoryXml = Category::whereId($category_id)->first()->xml_value;

        $attributeValue = $dom->createElementNS('urn:oasis:names:tc:SAML:2.0:assertion', 'saml:AttributeValue', $categoryXml);
        $attribute->appendChild($attributeValue);

        return $dom->saveXML();
    }


    //TODO ask about struct in SP and IDP in ResearchAndScholarship
    private function updateResearchAndScholarship( string $xml_document,bool $isIdp) : string
    {
        $dom = $this->createDOM($xml_document);
        $xPath = $this->createXPath($dom);

        $rootTag = $xPath->query("//*[local-name()='EntityDescriptor']")->item(0);


        $entityAttributes = $xPath->query('//mdattr:EntityAttributes');
        if ($entityAttributes->length === 0) {
            $entityAttributes = $dom->createElementNS( $this->mdattrURI,'mdattr:EntityAttributes');
            $rootTag->appendChild($entityAttributes);
        } else {
            $entityAttributes = $entityAttributes->item(0);
        }


        $attribute = $xPath->query('//mdattr:EntityAttributes/saml:Attribute', $entityAttributes);
        if ($attribute->length === 0) {
            $attribute = $dom->createElementNS($this->samlURI, 'saml:Attribute');
            $attribute->setAttribute('NameFormat', 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri');

            if($isIdp)
                $attribute->setAttribute('Name', 'http://macedir.org/entity-category-support');
            else
                $attribute->setAttribute('Name', 'http://macedir.org/entity-category');

            $entityAttributes->appendChild($attribute);
        } else {
            $attribute = $attribute->item(0);
        }

        $attributeValue = $dom->createElementNS($this->samlURI, 'saml:AttributeValue', 'http://refeds.org/category/research-and-scholarship');
        $attribute->appendChild($attributeValue);


        $dom->normalize();
       return $dom->saveXML();

    }





    public function updateEntitiesXml() : void
    {
        $this->mdURI = config('xmlNameSpace.md');
        $this->mdattrURI = config('xmlNameSpace.mdattr');
        $this->samlURI = config('xmlNameSpace.saml');

        foreach (Entity::select()->get() as $entity)
        {
            if(empty($entity->xml_file))
                continue;

            $xml_document = $entity->xml_file;
            $isIdp = false;
            if($entity->type == "idp")
                $isIdp = true;


            if($entity->rs)
            {
               $xml_document = $this->updateResearchAndScholarship($xml_document,$isIdp);
            }
            if(!empty($entity->category_id))
            {
                $xml_document = $this->updateXmlCategories($xml_document,$entity->category_id);
            }
            Entity::whereId($entity->id)->update(['xml_file' => $xml_document]);
        }
    }

    public function createEntities(int $adminId): void
    {
        $xmlfiles = [];
        $tagfiles = [];
        $unknown = [];

        foreach (Storage::files() as $file) {
            if (preg_match('/\.xml$/', $file)) {
                $xmlfiles[] = $file;
            }

            if (preg_match('/\.tag$/', $file)) {
                if (preg_match('/^' . config('git.edugain_tag') . '$/', $file)) {
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

            $xml_file = $this->deleteTags($metadata);

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
        foreach ($unknown as $ent) {
            Db::transaction(function () use ($adminId, $ent) {
                $entity = Entity::create($ent);

                $entity->approved = true;
                $entity->update();
                foreach ($ent['federations'] as $fed) {
                    if (!empty($fed)) {
                        $entity->federations()->attach($fed, [
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
}
