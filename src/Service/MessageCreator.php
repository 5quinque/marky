<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\MarkovKeyRepository;
use App\Repository\ValueRepository;
use App\Entity\MarkovKey;
use App\Entity\Value;

class MessageCreator
{
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

    public function getMessage(int $length = 80)
    {
        $seedEntity = $this->markovKeyRepository->getStartingPrefix();
        $seed = $seedEntity->getPair();

        $sentence = $seed;

        for ($i = 0; $i < $length; $i++) {
            $word = $this->getNextWord($seed);

            if ($word === "\n") {
                break;
            }

            $seed = $this->updateSeed($seed, $word);

            $sentence .= " $word";
        }
        
        return trim($sentence);
    }

    public function updateSeed(string $seed, string $suffix)
    {
        $seed .= " $suffix";

        // Remove first word, including whitespace
        return substr(strstr($seed," "), 1);
    }

    public function getNextWord(string $seed)
    {
        $markovKey = $this->markovKeyRepository->findOneBy(["pair" => $seed]);
        $values = $markovKey->getValue()->toArray();
        $index = array_rand($values);

        $v = $values[$index];

        return $v->getWord();
    }

}
