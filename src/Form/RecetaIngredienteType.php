<?php
// src/Form/RecetaIngredienteType.php

namespace App\Form;

use App\Entity\RecetaIngrediente;
use App\Entity\Ingrediente;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulario para asociar un ingrediente y su cantidad a una receta.
 * Permite seleccionar un ingrediente existente y especificar la cantidad.
 */
class RecetaIngredienteType extends AbstractType
{
    /**
     * Construye el formulario para un ingrediente de receta.
     * Incluye un select para el ingrediente y un campo de texto para la cantidad.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Selector de ingrediente (EntityType)
            ->add('ingrediente', EntityType::class, [
                'class' => Ingrediente::class,
                'choice_label' => 'nombre',
                'label' => 'Ingrediente',
                'label_attr' => ['style' => 'margin-right: 30px;'],
                'placeholder' => 'Selecciona un ingrediente',
                'attr' => ['class' => 'form-select'],
            ])
            // Campo de texto para la cantidad
            ->add('cantidad', TextType::class, [
                'label' => 'Cantidad',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    /**
     * Configura las opciones del formulario.
     * Asocia el formulario con la entidad RecetaIngrediente.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecetaIngrediente::class,
        ]);
    }
}