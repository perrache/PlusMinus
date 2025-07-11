<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $bo = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Currency $currency = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Organization $organization = null;

    /**
     * @var Collection<int, Minus>
     */
    #[ORM\OneToMany(targetEntity: Minus::class, mappedBy: 'account')]
    private Collection $minuses;

    /**
     * @var Collection<int, Plus>
     */
    #[ORM\OneToMany(targetEntity: Plus::class, mappedBy: 'account')]
    private Collection $pluses;

    #[ORM\Column(nullable: false)]
    private ?int $lt = null;

    #[ORM\Column(nullable: false)]
    private ?int $import = null;

    public function __construct()
    {
        $this->minuses = new ArrayCollection();
        $this->pluses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBo(): ?int
    {
        return $this->bo;
    }

    public function setBo(int $bo): static
    {
        $this->bo = $bo;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return Collection<int, Minus>
     */
    public function getMinuses(): Collection
    {
        return $this->minuses;
    }

    public function addMinus(Minus $minus): static
    {
        if (!$this->minuses->contains($minus)) {
            $this->minuses->add($minus);
            $minus->setAccount($this);
        }

        return $this;
    }

    public function removeMinus(Minus $minus): static
    {
        if ($this->minuses->removeElement($minus)) {
            // set the owning side to null (unless already changed)
            if ($minus->getAccount() === $this) {
                $minus->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Plus>
     */
    public function getPluses(): Collection
    {
        return $this->pluses;
    }

    public function addPlus(Plus $plus): static
    {
        if (!$this->pluses->contains($plus)) {
            $this->pluses->add($plus);
            $plus->setAccount($this);
        }

        return $this;
    }

    public function removePlus(Plus $plus): static
    {
        if ($this->pluses->removeElement($plus)) {
            // set the owning side to null (unless already changed)
            if ($plus->getAccount() === $this) {
                $plus->setAccount(null);
            }
        }

        return $this;
    }

    public function getLt(): ?int
    {
        return $this->lt;
    }

    public function setLt(?int $lt): static
    {
        $this->lt = $lt;

        return $this;
    }

    public function getImport(): ?int
    {
        return $this->import;
    }

    public function setImport(?int $import): static
    {
        $this->import = $import;

        return $this;
    }
}
