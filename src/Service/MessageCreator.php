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

    public function getMessage(string $seed, int $length = 20)
    {
        $sentence = "";

        $seedArr = explode(' ', $seed);
        $firstWord = $seedArr[0];
        $lastWord = $seedArr[1];

        for ($i = 0; $i < $length; $i++) {
            $inSeed = "$firstWord $lastWord";

            $word = $this->getNextWord($inSeed);
            $firstWord = $lastWord;
            $lastWord = $word;

            $sentence .= " $word";
        }
        
        return $sentence;
    }

    public function getNextWord(string $seed)
    {
        $markovKey = $this->markovKeyRepository->findOneBy(["pair" => $seed]);

        if (!$markovKey) {
            return ".";
        }

        $values = $markovKey->getValue()->toArray();

        $index = array_rand($values);

        $v = $values[$index];

        $sentence = $v->getWord();

        return $sentence;
    }

}
