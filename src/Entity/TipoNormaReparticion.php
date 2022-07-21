<?php

namespace App\Entity;

use App\Repository\TipoNormaReparticionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipoNormaReparticionRepository::class)
 */
class TipoNormaReparticion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=TipoNorma::class, inversedBy="tipoNormaReparticions")
     */
    private $tipoNormaId;

    /**
     * @ORM\ManyToOne(targetEntity=Area::class, inversedBy="tipoNormaReparticions")
     */
    private $reparticionId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoNormaId(): ?TipoNorma
    {
        return $this->tipoNormaId;
    }

    public function setTipoNormaId(?TipoNorma $tipoNormaId): self
    {
        $this->tipoNormaId = $tipoNormaId;

        return $this;
    }

    public function getReparticionId(): ?Area
    {
        return $this->reparticionId;
    }

    public function setReparticionId(?Area $reparticionId): self
    {
        $this->reparticionId = $reparticionId;

        return $this;
    }
}
