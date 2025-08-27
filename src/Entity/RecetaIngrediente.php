<?php

namespace App\Entity;

use App\Repository\RecetaIngredienteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidad RecetaIngrediente.
 * Representa la relación entre una receta y un ingrediente, incluyendo la cantidad utilizada.
 */
#[ORM\Entity(repositoryClass: RecetaIngredienteRepository::class)]
class RecetaIngrediente
{
    /**
     * Identificador único de la relación (clave primaria).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Cantidad del ingrediente utilizada en la receta (por ejemplo: "2 cucharadas", "150 g").
     */
    #[ORM\Column(length: 50)]
    private ?string $cantidad = null;

    /**
     * Relación ManyToOne con Receta.
     * Indica a qué receta pertenece este ingrediente.
     */
    #[ORM\ManyToOne(targetEntity: Receta::class, inversedBy: 'recetaIngredientes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Receta $receta = null;

    /**
     * Relación ManyToOne con Ingrediente.
     * Indica qué ingrediente es.
     */
    #[ORM\ManyToOne(targetEntity: Ingrediente::class)]
    private ?Ingrediente $ingrediente = null;

    /**
     * Obtiene el ID de la relación.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtiene la cantidad del ingrediente.
     */
    public function getCantidad(): ?string
    {
        return $this->cantidad;
    }

    /**
     * Establece la cantidad del ingrediente.
     */
    public function setCantidad(string $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    /**
     * Obtiene la receta asociada.
     */
    public function getReceta(): ?Receta
    {
        return $this->receta;
    }

    /**
     * Establece la receta asociada.
     */
    public function setReceta(?Receta $receta): static
    {
        $this->receta = $receta;

        return $this;
    }

    /**
     * Obtiene el ingrediente asociado.
     */
    public function getIngrediente(): ?Ingrediente
    {
        return $this->ingrediente;
    }

    /**
     * Establece el ingrediente asociado.
     */
    public function setIngrediente(?Ingrediente $ingrediente): static
    {
        $this->ingrediente = $ingrediente;

        return $this;
    }
}
