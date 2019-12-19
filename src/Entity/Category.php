<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hardware", mappedBy="category")
     */
    private $hardwares;

    public function __construct()
    {
        $this->hardwares = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Hardware[]
     */
    public function getHardwares(): Collection
    {
        return $this->hardwares;
    }

    public function addHardware(Hardware $hardware): self
    {
        if (!$this->hardwares->contains($hardware)) {
            $this->hardwares[] = $hardware;
            $hardware->setCategory($this);
        }

        return $this;
    }

    public function removeHardware(Hardware $hardware): self
    {
        if ($this->hardwares->contains($hardware)) {
            $this->hardwares->removeElement($hardware);
            // set the owning side to null (unless already changed)
            if ($hardware->getCategory() === $this) {
                $hardware->setCategory(null);
            }
        }

        return $this;
    }
}
