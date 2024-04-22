<?php
namespace App\Traits\DumpFromGit;

use App\Models\Category;
use App\Models\Entity;
use App\Models\Federation;
use App\Traits\ValidatorTrait;
use DOMNodeList;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

trait CreateEntitiesTrait{

    private string $mdURI = 'urn:oasis:names:tc:SAML:2.0:metadata';
    private string $mdattrURI = 'urn:oasis:names:tc:SAML:metadata:attribute';
    private string $samlURI = 'urn:oasis:names:tc:SAML:2.0:assertion';
    private string $mdrpiURI = 'urn:oasis:names:tc:SAML:metadata:rpi';

    use ValidatorTrait;


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
    private function deleteRegistrationInfo(\DOMXPath $xPath) : void
    {
        $xpathQuery = '//mdrpi:RegistrationInfo';
        $tags = $xPath->query($xpathQuery);
        if(!empty($tags))
        {
            foreach ($tags as $tag) {
                $this->deleteTag($tag);
            }
        }

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
        $this->deleteRegistrationInfo($xPath);


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
            $entityExtensions = $dom->createElementNS($this->mdURI, 'md:Extensions');
            $rootTag->appendChild($entityExtensions);
        }else {
            $entityExtensions = $entityExtensions->item(0);
        }
        $entityAttributes = $xPath->query('//mdattr:EntityAttributes');
        if ($entityAttributes->length === 0) {
            $entityAttributes = $dom->createElementNS($this->mdattrURI, 'mdattr:EntityAttributes');
            $entityExtensions->appendChild($entityAttributes);
        } else {
            $entityAttributes = $entityAttributes->item(0);
        }

        $attribute = $xPath->query('//mdattr:EntityAttributes/saml:Attribute', $entityAttributes);
        if ($attribute->length === 0) {

            $attribute = $dom->createElementNS($this->samlURI, 'saml:Attribute');

            $attribute->setAttribute('Name', 'http://macedir.org/entity-category');
            $attribute->setAttribute('NameFormat', 'urn:oasis:names:tc:SAML:2.0:attrname-format:uri');

            $entityAttributes->appendChild($attribute);
        } else {
            $attribute = $attribute->item(0);
        }

        $categoryXml = Category::whereId($category_id)->first()->xml_value;

        $attributeValue = $dom->createElementNS($this->samlURI, 'saml:AttributeValue', $categoryXml);
        $attribute->appendChild($attributeValue);

        return $dom->saveXML();
    }

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

    /**
     * @throws Exception if  exist more or less then 2 part something gone wrong
     */
    private function splitDocument():array
    {
        $document = Storage::get(config('git.reginfo'));
        $lines = explode("\n", $document);
        $splitDocument = [];

        foreach ($lines as $line) {
            if(empty(ltrim($line))) {
                continue;
            }
            $parts = preg_split('/\s+/', $line, 2);
            if(count($parts) != 2) {
                throw new Exception('no 2 part');
            } else
            {
                $splitDocument[$parts[0]] = $parts[1];
            }
        }
        return $splitDocument;
    }

    private function updateRegistrationInfo(string $xml_document, string $entityId,array $timestampDocumentArray ) : string
    {
        $dom = $this->createDOM($xml_document);
        $xPath = $this->createXPath($dom);
        $rootTag = $xPath->query("//*[local-name()='EntityDescriptor']")->item(0);

        $entityExtensions = $xPath->query('//md:Extensions');
        if ($entityExtensions->length === 0) {
            $entityExtensions = $dom->createElementNS( $this->mdURI,'md:Extensions');
            $rootTag->appendChild($entityExtensions);
        } else {
            $entityExtensions = $entityExtensions->item(0);
        }
        $info = $xPath->query('//mdrpi:RegistrationInfo', $entityExtensions);
        if ($info->length === 0) {

            $info = $dom->createElementNS($this->samlURI, 'mdrpi:RegistrationInfo');

            $info->setAttribute('registrationAuthority', config('registrationInfo.registrationAuthority'));


            if(empty($timestampDocumentArray[$entityId])) {
                $info->setAttribute('registrationInstant', gmdate('Y-m-d\TH:i:s\Z'));
            } else {
                $info->setAttribute('registrationInstant',$timestampDocumentArray[$entityId]);
            }


            $entityExtensions->appendChild($info);
        } else {
            $info = $info->item(0);
        }

        //For English
        $registrationPolicyEN = $dom->createElementNS($this->samlURI, 'saml:AttributeValue',config('registrationInfo.en'));
        $registrationPolicyEN->setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:lang', 'en');
        $info->appendChild($registrationPolicyEN);
        // For Czech
        $registrationPolicyCZ = $dom->createElementNS($this->samlURI, 'saml:AttributeValue',config('registrationInfo.cs'));
        $registrationPolicyCZ->setAttributeNS('http://www.w3.org/XML/1998/namespace', 'xml:lang', 'cs');
        $info->appendChild($registrationPolicyCZ);

        $dom->normalize();
        return $dom->saveXML();
    }




    //TODO with validator not working right before 104 and not working at all after
    public function updateEntitiesXml() : void
    {
        $this->mdURI = config('xmlNameSpace.md');
        $this->mdattrURI = config('xmlNameSpace.mdattr');
        $this->samlURI = config('xmlNameSpace.saml');
        $this->mdrpiURI = config('xmlNameSpace.mdrpi');

        $timestampDocumentArray = $this->splitDocument();
       // dump($timestampDocumentArray);


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

            $xml_document = $this->updateRegistrationInfo($xml_document,$entity->entityid,$timestampDocumentArray);

            $xml_document = $this->validateMetadata($xml_document);
            dump("hello ");
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
