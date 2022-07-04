<?php

namespace App\Entity;

use App\Repository\TipoConsultaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoConsultaRepository::class)
 */
class TipoConsulta
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Consulta::class, mappedBy="tipoConsulta")
     */
    private $consultas;
    public function __toString()
    {
        return $this->nombre;
    }
    public function __construct()
    {
        $this->consultas = new ArrayCollection();
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

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Consulta[]
     */
    public function getConsultas(): Collection
    {
        return $this->consultas;
    }

    public function addConsulta(Consulta $consulta): self
    {
        if (!$this->consultas->contains($consulta)) {
            $this->consultas[] = $consulta;
            $consulta->setTipoConsulta($this);
        }

        return $this;
    }

    public function removeConsulta(Consulta $consulta): self
    {
        if ($this->consultas->removeElement($consulta)) {
            // set the owning side to null (unless already changed)
            if ($consulta->getTipoConsulta() === $this) {
                $consulta->setTipoConsulta(null);
            }
        }

        return $this;
    }
}
