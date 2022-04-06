<?php

namespace App\Form;

use App\Entity\Norma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class LeyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('numero')
        ->add('titulo')
        ->add('fechaSancion')
        ->add('pdfFile', FileType::class, [
            'label' => 'Brochure (PDF file)',
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
        //->add('fechaPublicacion')
        ->add('resumen')
        ->add('texto',  FroalaEditorType::class)
        
        //->add('fechaPublicacionBoletin')
        //->add('estado')
        ->add('etiquetas')
        ->add('nueva_etiqueta',TextType::class, [
            'mapped' => false,
            'required' =>false
    ])
        ->add('decretoPromulgacion')
        ->add('fechaPromulgacion')
        ->add('temas')
        ->add('rela', CheckboxType::class, array(
            'required' => false,
            'value' => 1,
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