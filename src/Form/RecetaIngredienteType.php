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

class RecetaIngredienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ingrediente', EntityType::class, [
                'class' => Ingrediente::class,
                'choice_label' => 'nombre',
                'label' => 'Ingrediente',
                'label_attr' => ['style' => 'margin-right: 30px;'],
                'placeholder' => 'Selecciona un ingrediente',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('cantidad', TextType::class, [
                'label' => 'Cantidad',
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecetaIngrediente::class,
        ]);
    }
}