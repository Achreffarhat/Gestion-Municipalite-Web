<?php

namespace App\Entity;


use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message=" Rating doit etre non vide")
     * @Assert\LessThanOrEqual(
     *     value = 5
     * )
     * @Groups("posts:read")
     * @Groups("aviss")
    
     */


   
    private ?string $rating = null;

    #[ORM\Column(length: 255)]
     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message=" Commentaire doit etre non vide")
     * @Groups("posts:read")
     * @Groups("aviss")
     */


    private ?string $commentaire = null;

    #[ORM\Column(length: 255)]
    
     /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message=" Titre doit etre non vide")
     * @Groups("posts:read")
     * @Groups("aviss")
     */
    private ?string $titre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }
}
