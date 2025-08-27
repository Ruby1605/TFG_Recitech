<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ReturnTypeWillChange;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Entidad Usuario.
 * Representa a un usuario del sistema, con autenticación y roles.
 */
#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Identificador único del usuario (clave primaria).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nombre del usuario.
     */
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * Email del usuario (usado como identificador de login).
     */
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * Contraseña hasheada del usuario.
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * Rol principal del usuario (por ejemplo: ROLE_USER, ROLE_ADMIN).
     */
    #[ORM\Column(length: 20)]
    private ?string $rol = null;

    /**
     * Relación ManyToMany con Receta.
     * Un usuario puede tener varias recetas asociadas.
     * @var Collection<int, Receta>
     */
    #[ORM\ManyToMany(targetEntity: Receta::class)]
    private Collection $recetas;

    /**
     * Relación ManyToMany con Etiqueta.
     * Un usuario puede tener varias etiquetas asociadas.
     * @var Collection<int, Etiqueta>
     */
    #[ORM\ManyToMany(targetEntity: Etiqueta::class)]
    private Collection $etiquetas;

    /**
     * Constructor: inicializa las colecciones de recetas y etiquetas.
     */
    public function __construct()
    {
        $this->recetas = new ArrayCollection();
        $this->etiquetas = new ArrayCollection();
    }

    /**
     * Obtiene el ID del usuario.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtiene el nombre del usuario.
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * Establece el nombre del usuario.
     */
    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * Obtiene el email del usuario.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Establece el email del usuario.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Obtiene la contraseña hasheada del usuario.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Establece la contraseña hasheada del usuario.
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Obtiene el rol principal del usuario.
     */
    public function getRol(): ?string
    {
        return $this->rol;
    }

    /**
     * Establece el rol principal del usuario.
     */
    public function setRol(string $rol): static
    {
        $this->rol = $rol;
        return $this;
    }

    /**
     * @return Collection<int, Receta>
     * Obtiene las recetas asociadas al usuario.
     */
    public function getRecetas(): Collection
    {
        return $this->recetas;
    }

    /**
     * Añade una receta a la colección del usuario.
     */
    public function addReceta(Receta $receta): static
    {
        if (!$this->recetas->contains($receta)) {
            $this->recetas->add($receta);
        }
        return $this;
    }

    /**
     * Elimina una receta de la colección del usuario.
     */
    public function removeReceta(Receta $receta): static
    {
        $this->recetas->removeElement($receta);
        return $this;
    }

    /**
     * @return Collection<int, Etiqueta>
     * Obtiene las etiquetas asociadas al usuario.
     */
    public function getEtiquetas(): Collection
    {
        return $this->etiquetas;
    }

    /**
     * Añade una etiqueta a la colección del usuario.
     */
    public function addEtiqueta(Etiqueta $etiqueta): static
    {
        if (!$this->etiquetas->contains($etiqueta)) {
            $this->etiquetas->add($etiqueta);
        }
        return $this;
    }

    /**
     * Elimina una etiqueta de la colección del usuario.
     */
    public function removeEtiqueta(Etiqueta $etiqueta): static
    {
        $this->etiquetas->removeElement($etiqueta);
        return $this;
    }

    /**
     * Devuelve los roles del usuario para Symfony Security.
     */
    public function getRoles(): array
    {
        $roles = [$this->rol];

        // Garantiza que siempre tenga al menos ROLE_USER
        if (!in_array('ROLE_USER', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return $roles;
    }

    /**
     * Devuelve el salt para la contraseña (no necesario con bcrypt/sodium).
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Devuelve el identificador único del usuario (email).
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Borra credenciales temporales si las hubiera.
     */
    #[ReturnTypeWillChange]
    public function eraseCredentials(): void
    {
        // Si tienes datos temporales sensibles, bórralos aquí. Si no, deja vacío.
    }
}
