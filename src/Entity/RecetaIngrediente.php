<?php

namespace App\Entity;

use App\Repository\RecetaIngredienteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetaIngredienteRepository::class)]
class RecetaIngrediente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $cantidad = null;

    #[ORM\ManyToOne(targetEntity: Receta::class, inversedBy: 'recetaIngredientes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Receta $receta = null;

    #[ORM\ManyToOne(targetEntity: Ingrediente::class)]
    private ?Ingrediente $ingrediente = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidad(): ?string
    {
        return $this->cantidad;
    }

    public function setCantidad(string $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getReceta(): ?Receta
    {
        return $this->receta;
    }

    public function setReceta(?Receta $receta): static
    {
        $this->receta = $receta;

        return $this;
    }

    public function getIngrediente(): ?Ingrediente
    {
        return $this->ingrediente;
    }

    public function setIngrediente(?Ingrediente $ingrediente): static
    {
        $this->ingrediente = $ingrediente;

        return $this;
    }
}
