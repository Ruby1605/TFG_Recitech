<?php

namespace App\Entity;

use App\Repository\RecetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetaRepository::class)]
class Receta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $dificultad = null;

    #[ORM\Column]
    private ?int $tiempo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $explicacion = null;

    #[ORM\Column]
    private ?int $calorias = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagen = null;

    /**
     * @var Collection<int, Etiqueta>
     */
    #[ORM\ManyToMany(targetEntity: Etiqueta::class, inversedBy: 'recetas')]
    private Collection $etiquetas;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, RecetaIngrediente>
     */
    #[ORM\OneToMany(mappedBy: 'receta', targetEntity: RecetaIngrediente::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $recetaIngredientes;

    public function __construct()
    {
        $this->etiquetas = new ArrayCollection();
        $this->recetaIngredientes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDificultad(): ?string
    {
        return $this->dificultad;
    }

    public function setDificultad(string $dificultad): static
    {
        $this->dificultad = $dificultad;

        return $this;
    }

    public function getTiempo(): ?int
    {
        return $this->tiempo;
    }

    public function setTiempo(int $tiempo): static
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    public function getExplicacion(): ?string
    {
        return $this->explicacion;
    }

    public function setExplicacion(string $explicacion): static
    {
        $this->explicacion = $explicacion;

        return $this;
    }

    public function getCalorias(): ?int
    {
        return $this->calorias;
    }

    public function setCalorias(int $calorias): static
    {
        $this->calorias = $calorias;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): static
    {
        $this->imagen = $imagen;

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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, RecetaIngrediente>
     */
    public function getRecetaIngredientes(): Collection
    {
        return $this->recetaIngredientes;
    }

    public function addRecetaIngrediente(RecetaIngrediente $recetaIngrediente): static
    {
        if (!$this->recetaIngredientes->contains($recetaIngrediente)) {
            $this->recetaIngredientes->add($recetaIngrediente);
            $recetaIngrediente->setReceta($this);
        }

        return $this;
    }

    public function removeRecetaIngrediente(RecetaIngrediente $recetaIngrediente): static
    {
        if ($this->recetaIngredientes->removeElement($recetaIngrediente)) {
            // set the owning side to null (unless already changed)
            if ($recetaIngrediente->getReceta() === $this) {
                $recetaIngrediente->setReceta(null);
            }
        }

        return $this;
    }
}
