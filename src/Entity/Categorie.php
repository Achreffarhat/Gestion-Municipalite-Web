<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
      /**
     
     * @Groups("posts:read")
     * @Groups("categories")
     */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     
     * @Groups("posts:read")
     * @Groups("categories")
     */
    private ?string $labelcat = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Vehicule::class)]
    private Collection $vehicules;

    public function __construct()
    {
        $this->vehicules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelCat(): ?string
    {
        return $this->labelcat;
    }

    public function setLabelCat(string $labelcat): self
    {
        $this->labelcat = $labelcat;

        return $this;
    }

    /**
     * @return Collection<int, Vehicule>
     */
    public function getVehicules(): Collection
    {
        return $this->vehicules;
    }

    public function addVehicule(Vehicule $vehicule): self
    {
        if (!$this->vehicules->contains($vehicule)) {
            $this->vehicules->add($vehicule);
            $vehicule->setCategorie($this);
        }

        return $this;
    }

    public function removeVehicule(Vehicule $vehicule): self
    {
        if ($this->vehicules->removeElement($vehicule)) {
            // set the owning side to null (unless already changed)
            if ($vehicule->getCategorie() === $this) {
                $vehicule->setCategorie(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->labelcat; 
    }
}
