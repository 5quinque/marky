<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\MarkovKeyRepository;
use App\Repository\ValueRepository;
use App\Entity\MarkovKey;
use App\Entity\Value;

class MessageParser
{
    private $filename;

    private $json;

    private $om;
    private $markovKeyRepository;
    private $valueRepository;

    public function __construct(
        ObjectManager $objectManager,
        MarkovKeyRepository $markovKeyRepository,
        ValueRepository $valueRepository
    ) {
        $this->om = $objectManager;
        $this->markovKeyRepository = $markovKeyRepository;
        $this->valueRepository = $valueRepository;
    }

    public function setFile($filename)
    {
        if (!file_exists($filename)) {
            echo "$filename does not exist\n";
            return false;
        }

        $this->filename = $filename;
    }

    public function saveKey(MarkovKey $markovKey)
    {
        $this->om->persist($markovKey);
        $this->om->flush();
    }

    public function loadJSON()
    {
        $text = file_get_contents($this->filename);

        $this->json = json_decode($text);
    }

    public function loadMessages()
    {
        $this->loadJSON();

        foreach ($this->json->messages as $message) {
            $e = explode(' ', $message);

            for ($i = 0; $i < count($e) - 2; $i++) {
                $key = "{$e[$i]} {$e[$i+1]}";

                $markovKey = $this->loadKey($key);

                $value = $e[$i+2];
            }
        }
    }

    private function loadKey($key)
    {
        $markovKey = $this->markovKeyRepository->findOneBy(["pair" => $key]);

        if (!$markovKey) {
            $markovKey = new MarkovKey();
        
            $markovKey->setPair($key);
            $this->saveKey($markovKey);
        }

        return $markovKey;
    }
}
