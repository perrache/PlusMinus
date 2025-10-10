<?php

namespace App\Entity;

use App\Repository\MoveRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoveRepository::class)]
class Move
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

    #[ORM\ManyToOne(inversedBy: 'movesplus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $accplus = null;

    #[ORM\ManyToOne(inversedBy: 'movesminus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $accminus = null;

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

    public function getAccplus(): ?Account
    {
        return $this->accplus;
    }

    public function setAccplus(?Account $accplus): static
    {
        $this->accplus = $accplus;

        return $this;
    }

    public function getAccminus(): ?Account
    {
        return $this->accminus;
    }

    public function setAccminus(?Account $accminus): static
    {
        $this->accminus = $accminus;

        return $this;
    }
}
