<?php

namespace App\Entity;

use App\Repository\SourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourceRepository::class)]
class Source
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Plus>
     */
    #[ORM\OneToMany(targetEntity: Plus::class, mappedBy: 'source')]
    private Collection $pluses;

    public function __construct()
    {
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
            $plus->setSource($this);
        }

        return $this;
    }

    public function removePlus(Plus $plus): static
    {
        if ($this->pluses->removeElement($plus)) {
            // set the owning side to null (unless already changed)
            if ($plus->getSource() === $this) {
                $plus->setSource(null);
            }
        }

        return $this;
    }
}
