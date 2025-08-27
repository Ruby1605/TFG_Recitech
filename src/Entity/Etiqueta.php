<?php

namespace App\Entity;

use App\Repository\EtiquetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entidad Etiqueta.
 * Representa una etiqueta o categoría que puede asociarse a varias recetas (relación ManyToMany).
 */
#[ORM\Entity(repositoryClass: EtiquetaRepository::class)]
class Etiqueta
{
    /**
     * Identificador único de la etiqueta (clave primaria).
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Descripción o nombre de la etiqueta.
     */
    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    /**
     * Relación ManyToMany con la entidad Receta.
     * Una etiqueta puede estar asociada a muchas recetas.
     * El lado inverso se gestiona en la entidad Receta.
     * 
     * @var Collection<int, Receta>
     */
    #[ORM\ManyToMany(targetEntity: Receta::class, mappedBy: 'etiquetas')]
    private Collection $recetas;

    /**
     * Constructor: inicializa la colección de recetas.
     */
    public function __construct()
    {
        $this->recetas = new ArrayCollection();
    }

    /**
     * Obtiene el ID de la etiqueta.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtiene la descripción de la etiqueta.
     */
    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    /**
     * Establece la descripción de la etiqueta.
     */
    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Obtiene la colección de recetas asociadas a esta etiqueta.
     * 
     * @return Collection<int, Receta>
     */
    public function getRecetas(): Collection
    {
        return $this->recetas;
    }

    /**
     * Añade una receta a la colección de recetas asociadas.
     */
    public function addReceta(Receta $receta): static
    {
        if (!$this->recetas->contains($receta)) {
            $this->recetas->add($receta);
            $receta->addEtiqueta($this); // Mantiene la relación bidireccional
        }

        return $this;
    }

    /**
     * Elimina una receta de la colección de recetas asociadas.
     */
    public function removeReceta(Receta $receta): static
    {
        if ($this->recetas->removeElement($receta)) {
            $receta->removeEtiqueta($this); // Mantiene la relación bidireccional
        }

        return $this;
    }
}
