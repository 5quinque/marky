<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarkovKeyRepository")
 */
class MarkovKey
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
    private $pair;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Value", inversedBy="previousWords")
     */
    private $value;

    public function __construct()
    {
        $this->value = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPair(): ?string
    {
        return $this->pair;
    }

    public function setPair(string $pair): self
    {
        $this->pair = $pair;

        return $this;
    }

    /**
     * @return Collection|value[]
     */
    public function getValue(): Collection
    {
        return $this->value;
    }

    public function addValue(value $value): self
    {
        if (!$this->value->contains($value)) {
            $this->value[] = $value;
        }

        return $this;
    }

    public function removeValue(value $value): self
    {
        if ($this->value->contains($value)) {
            $this->value->removeElement($value);
        }

        return $this;
    }
}
