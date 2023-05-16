<?php

namespace App\Entity;
use App\Entity\Outils;
use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert; 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('reservation')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan("today")]
    #[Groups('reservation')]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Expression("this.getDateDebut() < this.getDateFin()",message:"La date fin ne doit pas être inferieur à la date début")]
    #[Groups('reservation')]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"le champ est obligatoire")] 
    #[Assert\Range(
        min: 1,
        max: 100,
        notInRangeMessage: 'Vous devez être entre {{ min }} et {{ max }} ', )]
        #[Groups('reservation')]
    private ?string $quantite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('reservation')]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Groups('reservation')]
    private ?Outils $outil = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getOutil(): ?Outils
    {
        return $this->outil;
    }

    public function setOutil(?Outils $outil): self
    {
        $this->outil = $outil;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
    public function __toString(){
        return (string) $this-> getId();
       


    }
}
