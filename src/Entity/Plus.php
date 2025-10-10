<?php

namespace App\Entity;

use App\Repository\PlusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlusRepository::class)]
class Plus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dat = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'pluses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\ManyToOne(inversedBy: 'pluses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Source $source = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getDat(): ?\DateTime
    {
        return $this->dat;
    }

    public function setDat(\DateTime $dat): static
    {
        $this->dat = $dat;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): static
    {
        $this->source = $source;

        return $this;
    }
}
