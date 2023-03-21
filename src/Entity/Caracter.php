<?php

namespace App\Entity;

use App\Repository\CaracterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CaracterRepository::class)
 */
class Caracter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     * Assert\NotNull()
     * Assert\NotBlank()
     * Assert\Lenght(min:3,max:16)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16)
     * Assert\NotNull()
     * Assert\NotBlank()
     * Assert\Lenght(min:3,max:64)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Assert\Lenght(min:3,max:255)
     */
    private $caste = null;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     * Assert\Lenght(min:3,max:16)
     */
    private $knowledge = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * Assert\PositiveOrZero
     */
    private $intelligence = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * Assert\PositiveOrZero
     */
    private $life = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * Assert\Lenght(min:3,max:128)
     */
    private $image = null;

    /**
     * @ORM\Column(type="string", length=16)
     * Assert\NotNull()
     * Assert\NotBlank()
     * Assert\Lenght(min:3,max:16)
     */
    private $kind;

    /**
     * @ORM\Column(type="date")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=40)
     * Assert\NotNull()
     * Assert\NotBlank()
     * Assert\Lenght(min:40,max:40)
     */
    private $identifier;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="caracters")
     */
    private $player;


    private $links = [];

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="caracters")
     */
    private $user;

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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCaste(): ?string
    {
        return $this->caste;
    }

    public function setCaste(?string $caste): self
    {
        $this->caste = $caste;

        return $this;
    }

    public function getKnowledge(): ?string
    {
        return $this->knowledge;
    }

    public function setKnowledge(?string $knowledge): self
    {
        $this->knowledge = $knowledge;

        return $this;
    }

    public function getIntelligence(): ?int
    {
        return $this->intelligence;
    }

    public function setIntelligence(?int $intelligence): self
    {
        $this->intelligence = $intelligence;

        return $this;
    }

    public function getLife(): ?int
    {
        return $this->life;
    }

    public function setLife(?int $life): self
    {
        $this->life = $life;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(string $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(?\DateTimeInterface $modified): self
    {
        $this->modified = $modified;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function setLinks(array $links): self
    {
        $this->links = $links;

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

}
