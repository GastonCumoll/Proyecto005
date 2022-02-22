<?php

namespace App\Form;

use App\Entity\Norma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenanzaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipoNorma')
            ->add('fechaSancion')
            ->add('fechaPublicacion')
            ->add('titulo')
            ->add('texto')
            ->add('resumen')
            //->add('fechaPublicacionBoletin')
            ->add('estado')
            //->add('etiquetas')
            ->add('numero')
            
            ->add('temas')
            ->add('fechaPromulgacion')
            ->add('decretoPromulgacion')
            ->add('complementadaPor')
            ->add('complementaA')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}