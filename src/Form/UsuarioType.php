<?php
namespace App\Form;

use App\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Formulario para crear o editar un usuario.
 * Incluye campos para nombre, correo electrónico, contraseña y rol.
 */
class UsuarioType extends AbstractType
{
    /**
     * Construye el formulario de usuario.
     * Define los campos y sus tipos.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Campo para el nombre del usuario
            ->add('nombre', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['class' => 'form-control'],
            ])
            // Campo para el correo electrónico
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'attr' => ['class' => 'form-control'],
            ])
            // Campo para la contraseña
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'attr' => ['class' => 'form-control'],
            ])
            // Campo para seleccionar el rol del usuario
            ->add('rol', ChoiceType::class, [
                'label' => 'Rol',
                'label_attr' => ['style' => 'margin-right: 30px;'],
                'choices' => [
                    'Administrador' => 'administrador',
                    'Usuario' => 'usuario',
                ],
                'placeholder' => 'Selecciona un rol',
                'attr' => ['class' => 'form-select'],
            ]);
    }

    /**
     * Configura las opciones del formulario.
     * Asocia el formulario con la entidad Usuario.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}