<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('contrasenia',PasswordType::class,[
            'label'=>'Contraseña anterior',
        ])
        ->add('contraseniaNueva',PasswordType::class,[
            'label'=>'Contraseña nueva',
        ])
        ->add('contraseniaNuevaConfir',PasswordType::class,[
            'label'=>'Confirmar contraseña',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => NULL,
        ]);
    }
}
