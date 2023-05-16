<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;    

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir un e-mail')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|tn)$/',
        message: 'L\'email "{{ value }}" n\'est pas valide. Veuillez saisir une adresse email valide se terminant par ".com" ou ".tn et contient "@"".'
    )]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre nom')]
    #[Assert\Regex(pattern:"/^\D+$/" , message:"Le nom ne doit pas contenir de chiffres")]
    private ?string $nomUtil = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre prenom')]
    #[Assert\Regex(pattern:"/^\D+$/" , 
                  message:"Le prenom ne doit pas contenir de chiffres")]
    private ?string $PrenomUtil = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message: 'Veuillez saisir votre numero')]
    #[Assert\Regex(pattern:"/^[259][0-9]{7}$/" , message:"Le numéro de téléphone doit commencer par 2, 5 ou 9 et avoir 8 chiffres au total.")]
    private ?int $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getNomUtil(): ?string
    {
        return $this->nomUtil;
    }

    public function setNomUtil(?string $nomUtil): self
    {
        $this->nomUtil = $nomUtil;

        return $this;
    }

    public function getPrenomUtil(): ?string
    {
        return $this->PrenomUtil;
    }

    public function setPrenomUtil(?string $PrenomUtil): self
    {
        $this->PrenomUtil = $PrenomUtil;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
}
