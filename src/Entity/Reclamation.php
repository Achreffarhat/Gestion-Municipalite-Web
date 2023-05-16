<?php

namespace App\Entity;
use Doctrine\DBAL\Types\Types;
use App\Repository\ReclamationRepository;
use App\Entity\Type;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /**
     * @Groups("posts:read")
     * @Groups("reclamations")
     */
    private ?int $id = null;

    #[ORM\Column(length: 255)]
      /**
     
     * @Groups("posts:read")
     * @Groups("reclamations")
     */
    private ?string $nom = null;

    #[ORM\Column(length: 255)]


/**
     * *@Assert\NotBlank(message=" Prenom doit etre non vide")
     * @Assert\Type("string",message="The value {{ value }} is not a valid {{ type }}.")
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Groups("posts:read")
     * @Groups("reclamations")
     */
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]

  /**
     * @Assert\NotBlank()
     * @Assert\Length(max="4096")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     * @ORM\Column(type="string", length=255)
     * @ORM\Column(name="email", type="string", length=500, nullable=false,unique=true)
     * @Groups("posts:read")
     * @Groups("reclamations")
     */
    private ?string $email = null;

    #[ORM\Column]
     
    private ?int $tel = null;

    #[ORM\Column(length: 255)] 
    

     /**
     
     * @Groups("posts:read")
     * @Groups("reclamations")
     */
    private ?string $etat = " en cours de traitement";

    #[ORM\Column(length: 255)]
      /**
     * *@Assert\NotBlank(message=" Description doit etre non vide")
     * @Assert\Type("string",message="The value {{ value }} is not a valid {{ type }}.")
     * @ORM\Column(type="string", length=255)
     * @Groups("posts:read")
     * @Groups("reclamations")
     */
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThan("today")]
    
    private ?\DateTimeInterface $date_reclamation = null;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    
    private ?Type $type = null;

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateReclamation(): ?\DateTimeInterface
    {
        return $this->date_reclamation;
    }

    public function setDateReclamation(\DateTimeInterface $date_reclamation): self
    {
        $this->date_reclamation = $date_reclamation;

        return $this;
    }

   

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

   
}
