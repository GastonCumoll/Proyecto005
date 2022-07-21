<?php

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AreaRepository::class)
 */
class Area
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=TipoNorma::class, mappedBy="area")
     */
    private $tipoNorma;

    /**
     * @ORM\OneToMany(targetEntity=TipoNormaReparticion::class, mappedBy="reparticionId")
     */
    private $tipoNormaReparticions;

    public function __construct()
    {
        $this->tipoNorma = new ArrayCollection();
        $this->tipoNormaReparticions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|TipoNorma[]
     */
    public function getTipoNorma(): Collection
    {
        return $this->tipoNorma;
    }

    public function addTipoNorma(TipoNorma $tipoNorma): self
    {
        if (!$this->tipoNorma->contains($tipoNorma)) {
            $this->tipoNorma[] = $tipoNorma;
            $tipoNorma->setArea($this);
        }

        return $this;
    }

    public function removeTipoNorma(TipoNorma $tipoNorma): self
    {
        if ($this->tipoNorma->removeElement($tipoNorma)) {
            // set the owning side to null (unless already changed)
            if ($tipoNorma->getArea() === $this) {
                $tipoNorma->setArea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TipoNormaReparticion[]
     */
    public function getTipoNormaReparticions(): Collection
    {
        return $this->tipoNormaReparticions;
    }

    public function addTipoNormaReparticion(TipoNormaReparticion $tipoNormaReparticion): self
    {
        if (!$this->tipoNormaReparticions->contains($tipoNormaReparticion)) {
            $this->tipoNormaReparticions[] = $tipoNormaReparticion;
            $tipoNormaReparticion->setReparticionId($this);
        }

        return $this;
    }

    public function removeTipoNormaReparticion(TipoNormaReparticion $tipoNormaReparticion): self
    {
        if ($this->tipoNormaReparticions->removeElement($tipoNormaReparticion)) {
            // set the owning side to null (unless already changed)
            if ($tipoNormaReparticion->getReparticionId() === $this) {
                $tipoNormaReparticion->setReparticionId(null);
            }
        }

        return $this;
    }
}
