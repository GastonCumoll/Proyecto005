<?php

namespace App\Form;

use App\Entity\Auditoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuditoriaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha')
            ->add('instanciaAnterior')
            ->add('instanciaActual')
            ->add('estadoAnterior')
            ->add('estadoActual')
            ->add('accion')
            ->add('usuario')
            ->add('norma')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auditoria::class,
        ]);
    }
}
