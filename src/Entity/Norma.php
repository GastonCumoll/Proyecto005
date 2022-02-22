<?php

namespace App\Entity;

use App\Repository\NormaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="array", nullable=true)
     */
    private $etiquetas = [];

    /**
     * @ORM\ManyToMany(targetEntity=Tema::class, mappedBy="normas")
     */
    private $temas;

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
     * @ORM\OneToMany(targetEntity=Norma::class, mappedBy="decretoPromulgacion")
     */
    private $normasPromulgadas;

    /**
     * @ORM\ManyToMany(targetEntity=Norma::class, inversedBy="complementaA")
     */
    private $complementadaPor;

    /**
     * @ORM\ManyToMany(targetEntity=Norma::class, mappedBy="complementadaPor")
     */
    private $complementaA;


    public function __toString()
    {
        return $this->titulo;
    }

    public function __construct()
    {
        $this->temas = new ArrayCollection();
        $this->normasPromulgadas = new ArrayCollection();
        $this->complementadaPor = new ArrayCollection();
        $this->complementaA = new ArrayCollection();
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

    public function getEtiquetas(): ?array
    {
        return $this->etiquetas;
    }

    public function setEtiquetas(?array $etiquetas): self
    {
        $this->etiquetas = $etiquetas;

        return $this;
    }

    /**
     * @return Collection|Tema[]
     */
    public function getTemas(): Collection
    {
        return $this->temas;
    }

    public function addTema(Tema $tema): self
    {
        if (!$this->temas->contains($tema)) {
            $this->temas[] = $tema;
            $tema->addNorma($this);
        }

        return $this;
    }

    public function removeTema(Tema $tema): self
    {
        if ($this->temas->removeElement($tema)) {
            $tema->removeNorma($this);
        }

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
     * @return Collection|self[]
     */
    public function getNormasPromulgadas(): Collection
    {
        return $this->normasPromulgadas;
    }

    public function addNormasPromulgada(self $normasPromulgada): self
    {
        if (!$this->normasPromulgadas->contains($normasPromulgada)) {
            $this->normasPromulgadas[] = $normasPromulgada;
            $normasPromulgada->setDecretoPromulgacion($this);
        }

        return $this;
    }

    public function removeNormasPromulgada(self $normasPromulgada): self
    {
        if ($this->normasPromulgadas->removeElement($normasPromulgada)) {
            // set the owning side to null (unless already changed)
            if ($normasPromulgada->getDecretoPromulgacion() === $this) {
                $normasPromulgada->setDecretoPromulgacion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getComplementadaPor(): Collection
    {
        return $this->complementadaPor;
    }

    public function addComplementadaPor(self $complementadaPor): self
    {
        if (!$this->complementadaPor->contains($complementadaPor)) {
            $this->complementadaPor[] = $complementadaPor;
        }

        return $this;
    }

    public function removeComplementadaPor(self $complementadaPor): self
    {
        $this->complementadaPor->removeElement($complementadaPor);

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getComplementaA(): Collection
    {
        return $this->complementaA;
    }

    public function addComplementaA(self $complementaA): self
    {
        if (!$this->complementaA->contains($complementaA)) {
            $this->complementaA[] = $complementaA;
            $complementaA->addComplementadaPor($this);
        }

        return $this;
    }

    public function removeComplementaA(self $complementaA): self
    {
        if ($this->complementaA->removeElement($complementaA)) {
            $complementaA->removeComplementadaPor($this);
        }

        return $this;
    }
}
