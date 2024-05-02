<?php
namespace App\Traits\DumpFromGit\EntitiesHelp;
use App\Models\Entity;
use App\Traits\ValidatorTrait;

trait FixEntityTrait{

    use ValidatorTrait;

    public function fixEntities(): void
    {
        foreach (Entity::select()->get() as $entity)
        {
            $xml_document = $entity->xml_file;

            //$xml_document = $entity->metadata;

            $res = json_decode($this->validateMetadata($xml_document,true),true);
            $res['ent_id'] = $entity->id;


            $errorArray = $res['errorArray'];

/*            if(array_key_exists('Logo',$errorArray))
            {
                $xml_document = $this->fixLogo($xml_document);
            }*/


            dump($res);
        }
    }


    private function fixLogo(string $xml_document): string
    {
        $dom = $this->createDOM($xml_document);
        $xPath = $this->createXPath($dom);



        return $dom->saveXML();
    }


}
