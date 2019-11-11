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

    private $messages;

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
    }

    public function saveValue(Value $value)
    {
        $this->om->persist($value);
    }

    public function loadJSON()
    {
        $text = file_get_contents($this->filename);

        $json = json_decode($text);

        $this->messages = $json->messages;
    }

    public function loadPlaintext()
    {
        $text = file_get_contents($this->filename);

        $this->messages = explode("\n", $text);
    }

    public function loadMessages(bool $json)
    {
        if ($json) {
            $this->loadJSON();
        } else {
            $this->loadPlaintext();
        }

        $this->parseMessages();
    }

    private function getKey(array $array, int $i)
    {
        // Start
        if ($i === 0) {
            $key = "\n {$array[$i]}";
        } elseif (!isset($array[$i+1])) {
            // End
            $key = "{$array[$i]} \n";
        } else {
            // Middle
            $key = "{$array[$i]} {$array[$i+1]}";
        }
        $markovKey = $this->loadKey($key);
       
        return $markovKey;
    }

    private function getValue(array $array, int $i)
    {
        if ($i === 0) {
            // Blank line
            if (!isset($array[$i+1])) {
                return false;
            }
            
            $value = $array[$i+1];
        } elseif (!isset($array[$i+2])) {
            $value = "\n";
        } else {
            $value = $array[$i+2];
        }

        $markovValue = $this->loadValue($value);

        return $markovValue;
    }

    public function parseMessages()
    {
        foreach ($this->messages as $message) {
            $e = explode(' ', $message);

            for ($i = 0; $i < count($e); $i++) {
                $markovKey = $this->getKey($e, $i);
                $markovValue = $this->getValue($e, $i);

                if (!$markovValue) {
                    continue;
                }

                $this->setValue($markovKey, $markovValue);
            }
            $this->om->flush();
        }
    }

    private function setValue(MarkovKey $markovKey, Value $markovValue)
    {
        $markovKey->addValue($markovValue);
        $this->saveKey($markovKey);
    }

    private function loadValue($value)
    {
        $markovValue = $this->valueRepository->findOneBy(["word" => $value]);

        if (!$markovValue) {
            $markovValue = new Value();

            $markovValue->setWord($value);
            $this->saveValue($markovValue);
        }

        return $markovValue;
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
