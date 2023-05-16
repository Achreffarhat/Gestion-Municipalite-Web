<?php

namespace App\Entity;

use App\Repository\OutilsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Mime\Message; 
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OutilsRepository::class)]
class Outils
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('outils')]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"le champ est obligatoire")]
    #[Groups('outils')]
    private ?string $label_outils = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"le champ est obligatoire")] 
    #[Assert\Range(
        min: 1,
        max: 100,
        notInRangeMessage: 'Vous devez être entre {{ min }} et {{ max }} ', )]
        #[Groups('outils')]
    private ?string $quantite = null;

    #[ORM\Column(length: 255)]
    #[Groups('outils')]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'outil', targetEntity: Reservation::class)]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabelOutils(): ?string
    {
        return $this->label_outils;
    }

    public function setLabelOutils(string $label_outils): self
    {
        $this->label_outils = $label_outils;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setOutil($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getOutil() === $this) {
                $reservation->setOutil(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return (string) $this-> getLabelOutils();
       


    }
}
