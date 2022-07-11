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
     * @ORM\OneToMany(targetEntity=Auditoria::class, mappedBy="usuario")
     */
    private $auditorias;

    // /**
    //  * @ORM\OneToMany(targetEntity=Auditoria::class, mappedBy="usuarioModificador")
    //  */
    // private $auditoriasMod;


    public function __toString()
    {
        return $this->nombre;
    }
    public function __construct()
    {
        //$this->normasCargadas = new ArrayCollection();
        $this->auditorias = new ArrayCollection();
        //$this->auditoriasMod = new ArrayCollection();
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

    // /**
    //  * @return Collection|Norma[]
    //  */
    // public function getNormasCargadas(): Collection
    // {
    //     return $this->normasCargadas;
    // }

    // public function addNormasCargada(Norma $normasCargada): self
    // {
    //     if (!$this->normasCargadas->contains($normasCargada)) {
    //         $this->normasCargadas[] = $normasCargada;
    //         $normasCargada->addUserCreador($this);
    //     }

    //     return $this;
    // }

    // public function removeNormasCargada(Norma $normasCargada): self
    // {
    //     if ($this->normasCargadas->removeElement($normasCargada)) {
    //         $normasCargada->removeUserCreador($this);
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|Auditoria[]
     */
    public function getAuditorias(): Collection
    {
        return $this->auditorias;
    }

    public function addAuditoria(Auditoria $auditoria): self
    {
        if (!$this->auditorias->contains($auditoria)) {
            $this->auditorias[] = $auditoria;
            $auditoria->setUsuario($this);
        }

        return $this;
    }

    public function removeAuditoria(Auditoria $auditoria): self
    {
        if ($this->auditorias->removeElement($auditoria)) {
            // set the owning side to null (unless already changed)
            if ($auditoria->getUsuario() === $this) {
                $auditoria->setUsuario(null);
            }
        }

        return $this;
    }
}
