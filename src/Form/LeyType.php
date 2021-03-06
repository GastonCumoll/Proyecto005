<?php

namespace App\Form;

use App\Entity\Norma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;

class LeyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('numero')
        ->add('fechaSancion')
        //->add('fechaPublicacion')
        ->add('titulo')
        ->add('texto',  FroalaEditorType::class)
        ->add('resumen')
        //->add('fechaPublicacionBoletin')
        //->add('estado')
        //->add('etiquetas',TextType::class)
        
        ->add('fechaPromulgacion')
        ->add('temas')
        ->add('rela', CheckboxType::class, array(
            'required' => false,
            'value' => 1,
        ))
        
        //->add('decretoPromulgacion')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}