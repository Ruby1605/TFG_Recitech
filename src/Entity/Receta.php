<?php

namespace App\Entity;

use App\Repository\RecetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidad Receta.
 * Representa una receta culinaria con sus propiedades y relaciones.
 */
#[ORM\Entity(repositoryClass: RecetaRepository::class)]
class Receta
{
    /**
     * Identificador único de la receta (clave primaria).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Dificultad de la receta (por ejemplo: Fácil, Media, Difícil).
     */
    #[ORM\Column(length: 10)]
    private ?string $dificultad = null;

    /**
     * Tiempo de preparación en minutos.
     */
    #[ORM\Column]
    private ?int $tiempo = null;

    /**
     * Explicación o pasos de la receta.
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $explicacion = null;

    /**
     * Nombre del archivo de la imagen asociada a la receta (opcional).
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imagen = null;

    /**
     * Relación ManyToMany con Etiqueta.
     * Una receta puede tener varias etiquetas (categorías).
     * @var Collection<int, Etiqueta>
     */
    #[ORM\ManyToMany(targetEntity: Etiqueta::class, inversedBy: 'recetas')]
    private Collection $etiquetas;

    /**
     * Nombre de la receta.
     */
    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    /**
     * Relación OneToMany con RecetaIngrediente.
     * Una receta puede tener varios ingredientes asociados.
     * @var Collection<int, RecetaIngrediente>
     */
    #[ORM\OneToMany(mappedBy: 'receta', targetEntity: RecetaIngrediente::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $recetaIngredientes;

    /**
     * Palabras clave asociadas a la receta (opcional).
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $palabrasClave = null;

    /**
     * Nacionalidad de la receta (por ejemplo: Española, Mexicana, etc.).
     */
    #[ORM\Column(length: 255)]
    private ?string $nacionalidad = null;

    /**
     * Número de porciones que rinde la receta (opcional).
     */
    #[ORM\Column(nullable: true)]
    private ?int $porciones = null;

    /**
     * Constructor: inicializa las colecciones de etiquetas e ingredientes.
     */
    public function __construct()
    {
        $this->etiquetas = new ArrayCollection();
        $this->recetaIngredientes = new ArrayCollection();
    }

    // Métodos getter y setter para cada propiedad

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
            // Elimina la relación inversa si corresponde
            if ($recetaIngrediente->getReceta() === $this) {
                $recetaIngrediente->setReceta(null);
            }
        }
        return $this;
    }

    public function getPalabrasClave(): ?string
    {
        return $this->palabrasClave;
    }

    public function setPalabrasClave(?string $palabrasClave): static
    {
        $this->palabrasClave = $palabrasClave;
        return $this;
    }

    public function getNacionalidad(): ?string
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(string $nacionalidad): static
    {
        $this->nacionalidad = $nacionalidad;
        return $this;
    }

    public function getPorciones(): ?int
    {
        return $this->porciones;
    }

    public function setPorciones(?int $porciones): static
    {
        $this->porciones = $porciones;
        return $this;
    }
}
