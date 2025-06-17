<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Receta;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\RecetaIngredienteType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class RecetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('explicacion')
            ->add('tiempo')
            ->add('dificultad')
            ->add('calorias')
            ->add('recetaIngredientes', CollectionType::class, [
                'entry_type' => RecetaIngredienteType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('imagen', FileType::class, [
                'label' => 'Imagen de la receta',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Receta::class,
        ]);
    }
}