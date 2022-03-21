<?php

namespace App\Entity;

use App\Repository\TemaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TemaRepository::class)
 */
class Tema
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
     * @ORM\ManyToOne(targetEntity=Capitulo::class, inversedBy="temas")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     */
    private $capitulo;

    /**
     * @ORM\ManyToMany(targetEntity=Norma::class, inversedBy="temas")
     * @MaxDepth(1)
     */
    private $normas;

    public function __toString()
    {
        return $this->nombre;
    }

    public function __construct()
    {
        $this->normas = new ArrayCollection();
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

    public function getCapitulo(): ?Capitulo
    {
        return $this->capitulo;
    }

    public function setCapitulo(?Capitulo $capitulo): self
    {
        $this->capitulo = $capitulo;

        return $this;
    }

    /**
     * @return Collection|Norma[]
     */
    public function getNormas(): Collection
    {
        return $this->normas;
    }

    public function addNorma(Norma $norma): self
    {
        if (!$this->normas->contains($norma)) {
            $this->normas[] = $norma;
        }

        return $this;
    }

    public function removeNorma(Norma $norma): self
    {
        $this->normas->removeElement($norma);

        return $this;
    }
}
