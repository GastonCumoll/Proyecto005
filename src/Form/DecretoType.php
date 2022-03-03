<?php

namespace App\Form;

use App\Entity\Norma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class DecretoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('numero')
        //->add('fechaSancion')
        //->add('fechaPublicacion')
        ->add('titulo')
        ->add('resumen')
        ->add('texto',  FroalaEditorType::class)
        //->add('fechaPublicacionBoletin')
        //->add('estado')
        //->add('etiquetas',TextType::class)
        
        //->add('fechaPromulgacion')
        ->add('temas')
        ->add('rela', CheckboxType::class, array(
            'required' => false,
            'value' => 1,
        ))
        //->add('decretoPromulgacion')
        //->add('cA')
        //->add('cPor')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}