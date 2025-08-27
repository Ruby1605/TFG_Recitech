<?php

namespace App\Entity;

use App\Repository\IngredienteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidad Ingrediente.
 * Representa un ingrediente que puede ser usado en recetas.
 */
#[ORM\Entity(repositoryClass: IngredienteRepository::class)]
class Ingrediente
{
    /**
     * Identificador único del ingrediente (clave primaria).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nombre del ingrediente.
     */
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * Obtiene el ID del ingrediente.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtiene el nombre del ingrediente.
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    /**
     * Establece el nombre del ingrediente.
     */
    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }
}
