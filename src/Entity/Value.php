<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ValueRepository")
 */
class Value
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $word;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MarkovKey", mappedBy="value")
     */
    private $previousWords;

    public function __construct()
    {
        $this->previousWords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;

        return $this;
    }

    /**
     * @return Collection|MarkovKey[]
     */
    public function getPreviousWords(): Collection
    {
        return $this->previousWords;
    }

    public function addPreviousWord(MarkovKey $previousWord): self
    {
        if (!$this->previousWords->contains($previousWord)) {
            $this->previousWords[] = $previousWord;
            $previousWord->addValue($this);
        }

        return $this;
    }

    public function removePreviousWord(MarkovKey $previousWord): self
    {
        if ($this->previousWords->contains($previousWord)) {
            $this->previousWords->removeElement($previousWord);
            $previousWord->removeValue($this);
        }

        return $this;
    }
}
