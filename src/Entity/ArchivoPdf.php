<?php

namespace App\Entity;

use App\Repository\ArchivoPdfRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArchivoPdfRepository::class)
 */
class ArchivoPdf
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
    private $ruta;

    /**
     * @ORM\ManyToOne(targetEntity=Norma::class, inversedBy="archivosPdf")
     */
    private $norma;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRuta(): ?string
    {
        return $this->ruta;
    }

    public function setRuta(string $ruta): self
    {
        $this->ruta = $ruta;

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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }
}
