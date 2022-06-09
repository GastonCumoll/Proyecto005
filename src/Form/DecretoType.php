<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Norma;
use App\Form\ItemType;
use App\Entity\Etiqueta;
use App\Repository\ItemRepository;
use App\Repository\EtiquetaRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DecretoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('numero')
        ->add('fechaSancion')
        //->add('fechaPublicacion')
        ->add('titulo')
        ->add('archivo', FileType::class,[
            'label'=> 'archivo',
            'label_attr'=>[
                'id'=>'subirArchi'
            ],
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false,
            'attr' => ['id'=>'archi']
        ])
        
        // ->add('pdfFile', FileType::class, [
        //     'attr' =>[
        //         'placeholder' => 'seleccione un archivo',
        //     ],
            
        //     'data_class' => null,
        //     'label' => '(PDF file)',
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

        ->add('resumen')
        ->add('texto',  CKEditorType::class,[
            'config' => [
                'toolbar' => 'full',
                //'uiColor' =>  '#FFFFFF',
                'removeButtons' => 'Save,NewPage',
                'filebrowserBrowseUrl' =>'/public/images',
                'filebrowserUploadUrl'=> '/public/images',
                //'removePlugins' => 'pasteimage',
                'pasteFilter' => 'h1 h2 p ul ol li; img[!src, alt]; a[!href]',
            ],
            'purify_html' => true,
        ])
        //->add('fechaPublicacionBoletin')
        //->add('estado')
        ->add('etiquetas',EntityType::class,[
            'required' => false,
            'class' => Etiqueta::class,
                'query_builder' => function(EtiquetaRepository $eti){
                    return $eti->createQueryBuilder('nombre')->orderBy('nombre.nombre','ASC');
                },
                'choice_label' => 'nombre',
                'multiple' => true,
                'attr' =>[
                    'class'=>'selectpicker',
                    'data-size'=>'10',
                    'data-live-search'=>true,
                ]
        ])
        ->add('etiquetas_de_norma',EntityType::class,[
            'mapped'=>false,
            'required' => false,
            'class' => Etiqueta::class,
                'query_builder' => function(EtiquetaRepository $eti){
                    
                    return $eti->createQueryBuilder('nombre')->orderBy('nombre.nombre','ASC');
                },
                'choice_label' => 'nombre',
                'multiple' => true,
                'attr' => ['name'=>'opcion']
        ]) 
        ->add('nueva_etiqueta',TextType::class, [
                'mapped' => false,
                'required' =>false
        ])
        
        //->add('fechaPromulgacion')
        
        ->add('items',EntityType::class,[
            'class' => Item::class,
            'multiple' =>true,
            'required' => false,
            'choice_label' => 'nombre',
            'attr'=> [
                'class'=>'selectpicker',
                'data-size'=>'10',
                'data-live-search'=>true,
            ]
            ]);
        
        // ->add('rela', CheckboxType::class, array(
        //     'required' => false,
        //     'value' => 1,
        //     'label' => '¿Está relacionada con otra norma?',
        // ))
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}