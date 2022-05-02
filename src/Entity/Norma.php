<?php

namespace App\Entity;

use App\Repository\NormaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NormaRepository::class)
 */
class Norma
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaSancion;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaPublicacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    private $texto;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resumen;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaPublicacionBoletin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity=TipoNorma::class, inversedBy="normas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoNorma;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $fechaPromulgacion;

    /**
     * @ORM\ManyToOne(targetEntity=Norma::class, inversedBy="normasPromulgadas")
     */
    private $decretoPromulgacion;

    /**
     * @ORM\OneToMany(targetEntity=Relacion::class, mappedBy="norma")
     * @MaxDepth(1)
     */
    private $complementa;

    /**
     * @ORM\OneToMany(targetEntity=Relacion::class, mappedBy="complementada")
     * @MaxDepth(1)
     */
    private $relaciones;

/**
* @ORM\Column(type="boolean")
*/
protected $rela = false;

/**
 * @ORM\ManyToMany(targetEntity=Etiqueta::class, inversedBy="normas")
 * @MaxDepth(1)
 */
private $etiquetas;


/**
 * @ORM\ManyToMany(targetEntity=Item::class, mappedBy="normas")
 */
private $items;

/**
 * @ORM\OneToMany(targetEntity=Archivo::class, mappedBy="norma")
 */
private $archivos;


public function getRela(): ?bool
{
    return $this->rela;
}

public function setRela(?bool $rela): self
{
    $this->rela = $rela;

    return $this;
}

    public function __toString()
    {
        return $this->titulo;
    }

    public function __construct()
    {
        $this->normasPromulgadas = new ArrayCollection();
        $this->complementa = new ArrayCollection();
        $this->relaciones = new ArrayCollection();
        $this->etiquetas = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->archivosPdf = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaSancion(): ?\DateTimeInterface
    {
        return $this->fechaSancion;
    }

    public function setFechaSancion(?\DateTimeInterface $fechaSancion): self
    {
        $this->fechaSancion = $fechaSancion;

        return $this;
    }

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechaPublicacion;
    }

    public function setFechaPublicacion(?\DateTimeInterface $fechaPublicacion): self
    {
        $this->fechaPublicacion = $fechaPublicacion;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getResumen(): ?string
    {
        return $this->resumen;
    }

    public function setResumen(?string $resumen): self
    {
        $this->resumen = $resumen;

        return $this;
    }

    public function getFechaPublicacionBoletin(): ?\DateTimeInterface
    {
        return $this->fechaPublicacionBoletin;
    }

    public function setFechaPublicacionBoletin(?\DateTimeInterface $fechaPublicacionBoletin): self
    {
        $this->fechaPublicacionBoletin = $fechaPublicacionBoletin;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getTipoNorma(): ?TipoNorma
    {
        return $this->tipoNorma;
    }

    public function setTipoNorma(?TipoNorma $tipoNorma): self
    {
        $this->tipoNorma = $tipoNorma;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getFechaPromulgacion(): ?\DateTimeInterface
    {
        return $this->fechaPromulgacion;
    }

    public function setFechaPromulgacion(?\DateTimeInterface $fechaPromulgacion): self
    {
        $this->fechaPromulgacion = $fechaPromulgacion;

        return $this;
    }

    public function getDecretoPromulgacion(): ?self
    {
        return $this->decretoPromulgacion;
    }

    public function setDecretoPromulgacion(?self $decretoPromulgacion): self
    {
        $this->decretoPromulgacion = $decretoPromulgacion;

        return $this;
    }

    /**
     * @return Collection|Relacion[]
     */
    public function getComplementa(): Collection
    {
        return $this->complementa;
    }

    public function addComplementum(Relacion $complementum): self
    {
        if (!$this->complementa->contains($complementum)) {
            $this->complementa[] = $complementum;
            $complementum->setNorma($this);
        }

        return $this;
    }

    public function removeComplementum(Relacion $complementum): self
    {
        if ($this->complementa->removeElement($complementum)) {
            // set the owning side to null (unless already changed)
            if ($complementum->getNorma() === $this) {
                $complementum->setNorma(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Relacion[]
     */
    public function getRelaciones(): Collection
    {
        return $this->relaciones;
    }

    public function addRelacione(Relacion $relacione): self
    {
        if (!$this->relaciones->contains($relacione)) {
            $this->relaciones[] = $relacione;
            $relacione->setComplementada($this);
        }

        return $this;
    }

    public function removeRelacione(Relacion $relacione): self
    {
        if ($this->relaciones->removeElement($relacione)) {
            // set the owning side to null (unless already changed)
            if ($relacione->getComplementada() === $this) {
                $relacione->setComplementada(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Etiqueta[]
     */
    public function getEtiquetas(): Collection
    {
        return $this->etiquetas;
    }

    public function addEtiqueta(Etiqueta $etiqueta): self
    {
        if (!$this->etiquetas->contains($etiqueta)) {
            $this->etiquetas[] = $etiqueta;
        }

        return $this;
    }

    public function removeEtiqueta(Etiqueta $etiqueta): self
    {
        $this->etiquetas->removeElement($etiqueta);

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->addNorma($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->removeElement($item)) {
            $item->removeNorma($this);
        }

        return $this;
    }

    /**
     * @return Collection|Archivo[]
     */
    public function getArchivos(): Collection
    {
        return $this->archivos;
    }

    public function addArchivos(Archivo $archivos): self
    {
        if (!$this->archivos->contains($archivos)) {
            $this->archivos[] = $archivos;
            $archivos->setNorma($this);
        }

        return $this;
    }

    public function removeArchivos(Archivo $archivos): self
    {
        if ($this->archivos->removeElement($archivos)) {
            // set the owning side to null (unless already changed)
            if ($archivos->getNorma() === $this) {
                $archivos->setNorma(null);
            }
        }

        return $this;
    }
}
