<?php

namespace App\Form;

use App\Entity\Norma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NormaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fechaSancion')
            ->add('fechaPublicacion')
            ->add('titulo')
            ->add('texto')
            ->add('resumen')
            ->add('fechaPublicacionBoletin')
            ->add('estado')
            ->add('etiquetas')
            ->add('numero')
            ->add('fechaPromulgacion')
            ->add('temas')
            ->add('tipoNorma')
            ->add('decretoPromulgacion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}
