<?php

namespace App\Entity;

use App\Repository\BanqueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BanqueRepository::class)]
class Banque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    private ?string $compteCourant = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $livretA = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $epargne = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    #[Assert\PositiveOrZero]
    private ?string $ticketRestaurant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompteCourant(): ?string
    {
        return $this->compteCourant;
    }

    public function setCompteCourant(string $compteCourant): self
    {
        $this->compteCourant = $compteCourant;

        return $this;
    }

    public function getLivretA(): ?string
    {
        return $this->livretA;
    }

    public function setLivretA(?string $livretA): self
    {
        $this->livretA = $livretA;

        return $this;
    }

    public function getEpargne(): ?string
    {
        return $this->epargne;
    }

    public function setEpargne(?string $epargne): self
    {
        $this->epargne = $epargne;

        return $this;
    }

    public function getTicketRestaurant(): ?string
    {
        return $this->ticketRestaurant;
    }

    public function setTicketRestaurant(?string $ticketRestaurant): self
    {
        $this->ticketRestaurant = $ticketRestaurant;

        return $this;
    }
}