<?php

namespace App\Entity;

use App\Repository\ConsultaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConsultaRepository::class)
 */
class Consulta
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
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity=TipoConsulta::class, inversedBy="consultas")
     */
    private $tipoConsulta;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeroTel;

    /**
     * @ORM\Column(type="text")
     */
    private $texto;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaYHora;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTipoConsulta(): ?TipoConsulta
    {
        return $this->tipoConsulta;
    }

    public function setTipoConsulta(?TipoConsulta $tipoConsulta): self
    {
        $this->tipoConsulta = $tipoConsulta;

        return $this;
    }

    public function getNumeroTel(): ?string
    {
        return $this->numeroTel;
    }

    public function setNumeroTel(?string $numeroTel): self
    {
        $this->numeroTel = $numeroTel;

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

    public function getFechaYHora(): ?\DateTimeInterface
    {
        return $this->fechaYHora;
    }

    public function setFechaYHora(?\DateTimeInterface $fechaYHora): self
    {
        $this->fechaYHora = $fechaYHora;

        return $this;
    }
}
