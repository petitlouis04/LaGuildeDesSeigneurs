<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * Assert\NotNull()
     * Assert\NotBlank()
     * Assert\Lenght(min:3,max:255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * Assert\NotNull()
     * Assert\NotBlank()
     * Assert\Lenght(min:3,max:255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * Assert\NotNull()
     * Assert\NotBlank()
     * Assert\Lenght(min:3,max:255)
     */
    private $email;

    /**
     * @ORM\Column(type="integer")
     * Assert\PositiveOrZero 
     */
    private $mirian;

    /**
     * @ORM\Column(type="string", length=255)
     * Assert\NotNull()
     * Assert\NotBlank()
     * Assert\Lenght(min:40,max:40)
     */
    private $identifier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getMirian(): ?int
    {
        return $this->mirian;
    }

    public function setMirian(int $mirian): self
    {
        $this->mirian = $mirian;

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

    public function toArray()
    {
        return get_object_vars($this);
    }
}
