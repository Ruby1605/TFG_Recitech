<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ReturnTypeWillChange;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    private ?string $rol = null;

    /**
     * @var Collection<int, Receta>
     */
    #[ORM\ManyToMany(targetEntity: Receta::class)]
    private Collection $recetas;

    /**
     * @var Collection<int, Etiqueta>
     */
    #[ORM\ManyToMany(targetEntity: Etiqueta::class)]
    private Collection $etiquetas;

    public function __construct()
    {
        $this->recetas = new ArrayCollection();
        $this->etiquetas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    /**
     * @return Collection<int, Receta>
     */
    public function getRecetas(): Collection
    {
        return $this->recetas;
    }

    public function addReceta(Receta $receta): static
    {
        if (!$this->recetas->contains($receta)) {
            $this->recetas->add($receta);
        }

        return $this;
    }

    public function removeReceta(Receta $receta): static
    {
        $this->recetas->removeElement($receta);

        return $this;
    }

    /**
     * @return Collection<int, Etiqueta>
     */
    public function getEtiquetas(): Collection
    {
        return $this->etiquetas;
    }

    public function addEtiqueta(Etiqueta $etiqueta): static
    {
        if (!$this->etiquetas->contains($etiqueta)) {
            $this->etiquetas->add($etiqueta);
        }

        return $this;
    }

    public function removeEtiqueta(Etiqueta $etiqueta): static
    {
        $this->etiquetas->removeElement($etiqueta);

        return $this;
    }

    public function getRoles(): array
    {
        $roles = [$this->rol];

        // Garantiza que siempre tenga al menos ROLE_USER
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    public function getSalt(): ?string
    {
        return null; // si usas bcrypt o sodium, retorna null
    }

    public function getUserIdentifier(): string
    {
        return $this->email; // o el campo que uses como identificador
    }

    #[ReturnTypeWillChange]
    public function eraseCredentials(): void
    {
        // Si tienes datos temporales sensibles, bórralos aquí. Si no, deja vacío.
    }
}
