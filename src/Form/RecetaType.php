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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RecetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('explicacion')
            ->add('tiempo')
            ->add('dificultad')
            ->add('porciones', IntegerType::class, [
                'label' => 'Porciones',
                'required' => true,
                'attr' => ['min' => 1],
            ])
            ->add('palabrasClave', TextType::class, [
                'label' => 'Palabras clave (separadas por comas)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ejemplo: postre, saludable, horno'
                ],
            ])
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
            ->add('nacionalidad', ChoiceType::class, [
                'choices' => [
                    'Mexicana' => 'Mexicana',
                    'Asiática' => 'Asiática',
                    'China' => 'China',
                    'Japonesa' => 'Japonesa',
                    'Española' => 'Española',
                    'Italiana' => 'Italiana',
                    'Francesa' => 'Francesa',
                    'Europea' => 'Europea',
                    'Sur América' => 'Sur América',
                    'Otras' => 'Otras',
                ],
                'expanded' => false, // select desplegable
                'multiple' => false, // solo una opción
                'label' => 'Nacionalidad',
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