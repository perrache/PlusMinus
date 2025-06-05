<?php

namespace App\Entity;

use App\Repository\Import1Repository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: Import1Repository::class)]
class Import1
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $postingdate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $valuedate = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $contractor = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $billsource = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $billdestination = null;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $value = null;

    #[ORM\Column(length: 50)]
    private ?string $transaction = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 50)]
    private ?string $category = null;

    #[ORM\Column]
    private ?int $last = null;

    #[ORM\Column]
    private ?int $use = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostingdate(): ?\DateTimeInterface
    {
        return $this->postingdate;
    }

    public function setPostingdate(\DateTimeInterface $postingdate): static
    {
        $this->postingdate = $postingdate;

        return $this;
    }

    public function getValuedate(): ?\DateTimeInterface
    {
        return $this->valuedate;
    }

    public function setValuedate(\DateTimeInterface $valuedate): static
    {
        $this->valuedate = $valuedate;

        return $this;
    }

    public function getContractor(): ?string
    {
        return $this->contractor;
    }

    public function setContractor(?string $contractor): static
    {
        $this->contractor = $contractor;

        return $this;
    }

    public function getBillsource(): ?string
    {
        return $this->billsource;
    }

    public function setBillsource(?string $billsource): static
    {
        $this->billsource = $billsource;

        return $this;
    }

    public function getBilldestination(): ?string
    {
        return $this->billdestination;
    }

    public function setBilldestination(?string $billdestination): static
    {
        $this->billdestination = $billdestination;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getTransaction(): ?string
    {
        return $this->transaction;
    }

    public function setTransaction(string $transaction): static
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getLast(): ?int
    {
        return $this->last;
    }

    public function setLast(int $last): static
    {
        $this->last = $last;

        return $this;
    }

    public function getUse(): ?int
    {
        return $this->use;
    }

    public function setUse(int $use): static
    {
        $this->use = $use;

        return $this;
    }
}
