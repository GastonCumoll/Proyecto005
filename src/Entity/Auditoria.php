<?php

namespace App\Entity;

use App\Repository\AuditoriaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuditoriaRepository::class)
 */
class Auditoria
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;


    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $instanciaAnterior;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $instanciaActual;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $estadoAnterior;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $estadoActual;

    /**
     * @ORM\ManyToOne(targetEntity=Norma::class, inversedBy="auditorias")
     */
    private $norma;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $accion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreUsuario;

    public function __toString()
    {
        return $this->$usuario;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getInstanciaAnterior(): ?int
    {
        return $this->instanciaAnterior;
    }

    public function setInstanciaAnterior(?int $instanciaAnterior): self
    {
        $this->instanciaAnterior = $instanciaAnterior;

        return $this;
    }

    public function getInstanciaActual(): ?int
    {
        return $this->instanciaActual;
    }

    public function setInstanciaActual(?int $instanciaActual): self
    {
        $this->instanciaActual = $instanciaActual;

        return $this;
    }

    public function getEstadoAnterior(): ?string
    {
        return $this->estadoAnterior;
    }

    public function setEstadoAnterior(?string $estadoAnterior): self
    {
        $this->estadoAnterior = $estadoAnterior;

        return $this;
    }

    public function getEstadoActual(): ?string
    {
        return $this->estadoActual;
    }

    public function setEstadoActual(?string $estadoActual): self
    {
        $this->estadoActual = $estadoActual;

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

    public function getAccion(): ?string
    {
        return $this->accion;
    }

    public function setAccion(?string $accion): self
    {
        $this->accion = $accion;

        return $this;
    }

    public function getNombreUsuario(): ?string
    {
        return $this->nombreUsuario;
    }

    public function setNombreUsuario(?string $nombreUsuario): self
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }
}
