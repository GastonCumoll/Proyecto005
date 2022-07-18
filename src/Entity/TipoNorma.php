<?php

namespace App\Entity;

use App\Repository\TipoNormaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoNormaRepository::class)
 */
class TipoNorma
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
     * @ORM\OneToMany(targetEntity=Norma::class, mappedBy="tipoNorma", orphanRemoval=true)
     */
    private $normas;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rol;
    public function __toString()
    {
        return $this->nombre;
    }

    public function __construct()
    {
        $this->normas = new ArrayCollection();
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
     * @return Collection|Norma[]
     */
    public function getNormas(): Collection
    {
        return $this->normas;
    }

    public function addNorma(Norma $norma): self
    {
        if (!$this->normas->contains($norma)) {
            $this->normas[] = $norma;
            $norma->setTipoNorma($this);
        }

        return $this;
    }

    public function removeNorma(Norma $norma): self
    {
        if ($this->normas->removeElement($norma)) {
            // set the owning side to null (unless already changed)
            if ($norma->getTipoNorma() === $this) {
                $norma->setTipoNorma(null);
            }
        }

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(?string $rol): self
    {
        $this->rol = $rol;

        return $this;
    }
}
