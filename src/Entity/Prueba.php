<?php

namespace App\Entity;

use App\Repository\PruebaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PruebaRepository::class)
 */
class Prueba
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titulo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $texto;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $indexdate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $publishStartDate;

    /**
     * @ORM\Column(type="string", length=10000, nullable=true)
     */
    private $TextoLargo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(?string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    public function getIndexdate(): ?\DateTimeInterface
    {
        return $this->indexdate;
    }

    public function setIndexdate(?\DateTimeInterface $indexdate): self
    {
        $this->indexdate = $indexdate;

        return $this;
    }

    public function getPublishStartDate(): ?\DateTimeInterface
    {
        return $this->publishStartDate;
    }

    public function setPublishStartDate(?\DateTimeInterface $publishStartDate): self
    {
        $this->publishStartDate = $publishStartDate;

        return $this;
    }

    public function getTextoLargo(): ?string
    {
        return $this->TextoLargo;
    }

    public function setTextoLargo(?string $TextoLargo): self
    {
        $this->TextoLargo = $TextoLargo;

        return $this;
    }
}
