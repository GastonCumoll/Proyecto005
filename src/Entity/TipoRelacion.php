<?php

namespace App\Entity;

use App\Repository\TipoRelacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoRelacionRepository::class)
 */
class TipoRelacion
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
     * @ORM\OneToMany(targetEntity=Relacion::class, mappedBy="tipoRelacion")
     */
    private $rela;

    /**
     * @ORM\OneToOne(targetEntity=TipoRelacion::class, cascade={"persist", "remove"})
     */
    private $inverso;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prioridad;

    public function __toString()
    {
        return $this->nombre;
    }

    public function __construct()
    {
        $this->rela = new ArrayCollection();
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
     * @return Collection|Relacion[]
     */
    public function getRela(): Collection
    {
        return $this->rela;
    }

    public function addRela(Relacion $rela): self
    {
        if (!$this->rela->contains($rela)) {
            $this->rela[] = $rela;
            $rela->setTipoRelacion($this);
        }

        return $this;
    }

    public function removeRela(Relacion $rela): self
    {
        if ($this->rela->removeElement($rela)) {
            // set the owning side to null (unless already changed)
            if ($rela->getTipoRelacion() === $this) {
                $rela->setTipoRelacion(null);
            }
        }

        return $this;
    }

    public function getInverso(): ?self
    {
        return $this->inverso;
    }

    public function setInverso(?self $inverso): self
    {
        $this->inverso = $inverso;

        return $this;
    }

    public function getPrioridad(): ?int
    {
        return $this->prioridad;
    }

    public function setPrioridad(?int $prioridad): self
    {
        $this->prioridad = $prioridad;

        return $this;
    }
}
