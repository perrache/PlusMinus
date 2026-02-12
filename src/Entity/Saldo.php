<?php

namespace App\Entity;

use App\Repository\SaldoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaldoRepository::class)]
class Saldo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dat = null;

    #[ORM\Column]
    private ?int $curid = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCurid(): ?int
    {
        return $this->curid;
    }

    public function setCurid(int $curid): static
    {
        $this->curid = $curid;

        return $this;
    }
}
