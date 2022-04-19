<?php

namespace App\Form;

use App\Entity\Norma;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;

class DirectivaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('titulo')
            ->add('archivo', FileType::class,[
                'label'=> 'archivo',
                'multiple'=>true,
                'mapped'=>false,
                'required'=>false
            ])
            // ->add('pdfFile', FileType::class, [
            //     'data_class' => null,
            //     'label' => 'Brochure (PDF file)',
            //     'required' => false,
            //     'constraints' => [
            //         new File([
            //             'maxSize' => '1048576k',
            //             'mimeTypes' => [
            //                 'application/pdf',
            //                 'application/x-pdf',
            //             ],
            //             'mimeTypesMessage' => 'Please upload a valid PDF document',
            //         ])
            // ]])
            //->add('fechaSancion')
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
            //->add('fechaPromulgacion')

            ->add('items')
            ->add('rela', CheckboxType::class, array(
                'required' => false,
                'value' => 1,
                'label' => '¿Está relacionada con otra norma?'
            ))
            //->add('decretoPromulgacion')
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}