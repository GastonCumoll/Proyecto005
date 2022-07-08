<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsuarioRepository::class)
 */
class Usuario
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
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rol;

    /**
     * @ORM\ManyToMany(targetEntity=Norma::class, mappedBy="userCreador")
     */
    private $normasCargadas;
    public function __toString()
    {
        return $this->nombre;
    }
    public function __construct()
    {
        $this->normasCargadas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(?string $rol): self
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * @return Collection|Norma[]
     */
    public function getNormasCargadas(): Collection
    {
        return $this->normasCargadas;
    }

    public function addNormasCargada(Norma $normasCargada): self
    {
        if (!$this->normasCargadas->contains($normasCargada)) {
            $this->normasCargadas[] = $normasCargada;
            $normasCargada->addUserCreador($this);
        }

        return $this;
    }

    public function removeNormasCargada(Norma $normasCargada): self
    {
        if ($this->normasCargadas->removeElement($normasCargada)) {
            $normasCargada->removeUserCreador($this);
        }

        return $this;
    }
}
