<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTimeInterface;


#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("rendezvous")]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:" description doit etre non vide")]
    #[Groups("rendezvous")]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\GreaterThan("today")]
    #[Groups("rendezvous")]
    private ?\DateTimeInterface $date_ren = null;

    #[ORM\ManyToOne(inversedBy: 'rendez_vous')]
    #[Groups("rendezvous")]
    private ?User $users = null;
    //   /**
    //  * @var array
    //  */
    // protected $options = [];

    // /**
    //  * @var string
    //  */
    // protected $resourceId;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateRen(): ?\DateTimeInterface
    {
        return $this->date_ren;
    }

    public function setDateRen(\DateTimeInterface $date_ren): self
    {
        $this->date_ren = $date_ren;

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
    // public function getResourceId(): ?string
    // {
    //     return $this->resourceId;
    // }

    // public function setResourceId(?string $resourceId): void
    // {
    //     $this->resourceId = $resourceId;
    // }

    // public function getOptions(): array
    // {
    //     return $this->options;
    // }

    // public function setOptions(array $options): void
    // {
    //     $this->options = $options;
    // }

    // /**
    //  * @param string|int $name
    //  */
    // public function getOption($name)
    // {
    //     return $this->options[$name];
    // }

    // /**
    //  * @param string|int $name
    //  */
    // public function addOption($name, $value): void
    // {
    //     $this->options[$name] = $value;
    // }

    // /**
    //  * @param string|int $name
    //  *
    //  * @return mixed|null
    //  */
    // public function removeOption($name)
    // {
    //     if (!isset($this->options[$name]) && !\array_key_exists($name, $this->options)) {
    //         return null;
    //     }

    //     $removed = $this->options[$name];
    //     unset($this->options[$name]);

    //     return $removed;
    // }

    // public function toArray(): array
    // {
    //     $event = [
    //         'title' => $this->getDescription(),
    //         'start' => $this->getDateRen()
            
    //     ];
    //     return array_merge($event, $this->getOptions());
    // }
    // public function __toString()
    // {

    //     $result = $this->date_ren ;
    //     return  (string) $result;
    // }
}


