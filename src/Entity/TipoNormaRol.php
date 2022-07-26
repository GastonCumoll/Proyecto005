<?php

namespace App\Entity;

use App\Repository\TipoNormaRolRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoNormaRolRepository::class)
 */
class TipoNormaRol
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TipoNorma::class, inversedBy="tipoNormaRoles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tipoNorma;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombreRol;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getNombreRol(): ?string
    {
        return $this->nombreRol;
    }

    public function setNombreRol(?string $nombreRol): self
    {
        $this->nombreRol = $nombreRol;

        return $this;
    }
}
