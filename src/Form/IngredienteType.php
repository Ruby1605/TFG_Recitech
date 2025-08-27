<?php

namespace App\Form;

use App\Entity\Ingrediente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Formulario para crear o editar un ingrediente.
 */
class IngredienteType extends AbstractType
{
    /**
     * Construye el formulario de ingrediente.
     * Añade un campo de texto para el nombre del ingrediente.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
            ]);
    }

    /**
     * Configura las opciones del formulario.
     * Asocia el formulario con la entidad Ingrediente.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingrediente::class,
        ]);
    }
}