<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("documents")]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("documents")]

    // #[Assert\NotBlank(message:" image doit etre /gif, /jpeg , /jpg , /png")]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'Documents')]
    #[Groups("documents")]

    private ?User $users = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:" name doit etre non vide")]
    #[Groups("documents")]

    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getimage(): ?string
    {
        return $this->image;
    }

    public function setimage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
