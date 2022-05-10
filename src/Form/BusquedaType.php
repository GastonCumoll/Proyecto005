<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BusquedaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo',TextType::class,[
                'required' => false,
                'mapped' => false
            ])
            ->add('tipo',TextType::class,[
                'required' => false,
                'mapped' => false
                ])
            ->add('numero',TextType::class,[
                'required' => false,
                'mapped' => false
                ])
            ->add('anio',TextType::class,[
                'required' => false,
                'mapped' => false,
                'label' => 'Año'
                ])
            ->add('etiquetas',TextType::class,[
                'required' => false,
                'mapped' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // $resolver->setDefaults([
        //     'data_class' => TextType::class,
        // ]);
    }
}
