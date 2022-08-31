<?php

namespace App\Entity;

use App\Repository\RelacionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RelacionRepository::class)
 */
class Relacion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $fechaRelacion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resumen;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $usuario;

    /**
     * @ORM\ManyToOne(targetEntity=Norma::class, inversedBy="complementa")
     */
    private $norma;

    /**
     * @ORM\ManyToOne(targetEntity=Norma::class, inversedBy="relaciones")
     */
    private $complementada;

    /**
     * @ORM\ManyToOne(targetEntity=TipoRelacion::class, inversedBy="rela")
     */
    private $tipoRelacion;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFechaRelacion(): ?\DateTimeInterface
    {
        return $this->fechaRelacion;
    }

    public function setFechaRelacion(\DateTimeInterface $fechaRelacion): self
    {
        $this->fechaRelacion = $fechaRelacion;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

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

    public function getUsuario(): ?string
    {
        return $this->usuario;
    }

    public function setUsuario(string $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getNorma(): ?Norma
    {
        return $this->norma;
    }

    public function setNorma(?Norma $norma): self
    {
        $this->norma = $norma;

        return $this;
    }

    public function getComplementada(): ?Norma
    {
        return $this->complementada;
    }

    public function setComplementada(?Norma $complementada): self
    {
        $this->complementada = $complementada;

        return $this;
    }

    public function getTipoRelacion(): ?TipoRelacion
    {
        return $this->tipoRelacion;
    }

    public function setTipoRelacion(?TipoRelacion $tipoRelacion): self
    {
        $this->tipoRelacion = $tipoRelacion;

        return $this;
    }
}
