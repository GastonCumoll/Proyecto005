<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use App\Repository\ItemRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
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
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $orden;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="dependencias")
     * @ORM\JoinColumn(nullable=true)
     */
    private $padre;

    /**
     * @ORM\OneToMany(targetEntity=Item::class, mappedBy="padre", orphanRemoval=true)
     * @OrderBy({"nombre" = "ASC"})
     */
    private $dependencias;

    /**
     * @ORM\ManyToMany(targetEntity=Norma::class, inversedBy="items")
     */
    private $normas;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $enlazado;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $editar;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenido;

    public function __construct()
    {
        $this->dependencias = new ArrayCollection();
        $this->normas = new ArrayCollection();
        $this->padre = null;
        $this->orden = 0;
        
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

    public function getOrden(): ?int
    {
        return $this->orden;
    }

    public function setOrden(int $orden): self
    {
        $this->orden = $orden;

        return $this;
    }

    public function getPadre(): ?self
    {
        return $this->padre;
    }

    public function setPadre(?self $padre): self
    {
        $this->padre = $padre;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getDependencias(): Collection
    {
        return $this->dependencias;
    }

    public function addDependencia(self $dependencia): self
    {
        if (!$this->dependencias->contains($dependencia)) {
            $this->dependencias[] = $dependencia;
            $dependencia->setPadre($this);
        }

        return $this;
    }

    public function removeDependencia(self $dependencia): self
    {
        if ($this->dependencias->removeElement($dependencia)) {
            // set the owning side to null (unless already changed)
            if ($dependencia->getPadre() === $this) {
                $dependencia->setPadre(null);
            }
        }

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
        }

        return $this;
    }

    public function removeNorma(Norma $norma): self
    {
        $this->normas->removeElement($norma);

        return $this;
    }

    public function getEnlazado(): ?int
    {
        return $this->enlazado;
    }

    public function setEnlazado(?int $enlazado): self
    {
        $this->enlazado = $enlazado;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getEditar(): ?int
    {
        return $this->editar;
    }

    public function setEditar(?int $editar): self
    {
        $this->editar = $editar;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(?string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }
}
