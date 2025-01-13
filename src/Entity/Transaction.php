<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Minus>
     */
    #[ORM\OneToMany(targetEntity: Minus::class, mappedBy: 'transaction')]
    private Collection $minuses;

    public function __construct()
    {
        $this->minuses = new ArrayCollection();
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
            $minus->setTransaction($this);
        }

        return $this;
    }

    public function removeMinus(Minus $minus): static
    {
        if ($this->minuses->removeElement($minus)) {
            // set the owning side to null (unless already changed)
            if ($minus->getTransaction() === $this) {
                $minus->setTransaction(null);
            }
        }

        return $this;
    }
}
