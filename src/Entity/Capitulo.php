<?php

namespace App\Entity;

use App\Repository\CapituloRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CapituloRepository::class)
 */
class Capitulo
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
     * @ORM\ManyToOne(targetEntity=Titulo::class, inversedBy="capitulos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $titulo;

    /**
     * @ORM\OneToMany(targetEntity=Tema::class, mappedBy="capitulo", orphanRemoval=true)
     */
    private $temas;

    public function __construct()
    {
        $this->temas = new ArrayCollection();
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

    public function getTitulo(): ?Titulo
    {
        return $this->titulo;
    }

    public function setTitulo(?Titulo $titulo): self
    {
        $this->titulo = $titulo;

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
            $tema->setCapitulo($this);
        }

        return $this;
    }

    public function removeTema(Tema $tema): self
    {
        if ($this->temas->removeElement($tema)) {
            // set the owning side to null (unless already changed)
            if ($tema->getCapitulo() === $this) {
                $tema->setCapitulo(null);
            }
        }

        return $this;
    }
}
