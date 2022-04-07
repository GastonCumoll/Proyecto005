<?php

namespace App\Form;

use App\Entity\Norma;
use App\Form\ItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        ->add('pdfFile', FileType::class, [
            'attr' =>[
                'placeholder' => 'seleccione un archivo',
            ],
            'data_class' => null,
            'label' => '(PDF file)',
            'required' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1048576k',
                    'mimeTypes' => [
                        'application/pdf',
                        'application/x-pdf',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid PDF document',
                ])
        ]])

        ->add('resumen')
        ->add('texto',  FroalaEditorType::class)
        //->add('fechaPublicacionBoletin')
        //->add('estado')
        ->add('etiquetas')
        ->add('nueva_etiqueta',TextType::class, [
                'mapped' => false,
                'required' =>false
        ])
        
        //->add('fechaPromulgacion')
        
        //->add('temas')
        ->add('items')
        ->add('rela', CheckboxType::class, array(
            'required' => false,
            'value' => 1,
            'label' => '¿Está relacionada con otra norma?',
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}