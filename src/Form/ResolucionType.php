<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Norma;
use App\Entity\Etiqueta;
use App\Repository\EtiquetaRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ResolucionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //numeroAuxiliar
        ->add('numeroAuxiliar',NumberType::class,[
            'label' => 'Número',
            'help' => 'Solo números',
            'required' => false,
        ]
        )
        //titulo
        ->add('titulo',TextType::class,[
            'label'=> 'Titulo (*)',
            'required' => false,
            'constraints'=>[new NotBlank(),],
        ])
        //fechaSancion
        ->add('fechaSancion',DateType::class,[
            'required' => false,
            'widget' =>'single_text',
            'html5'=>false,
            'format'=> 'dd/MM/yyyy',
            'label' => 'Fecha de sancion',
            'attr'=>[
                'class' => 'datepicker ',
                'style' => 'min-width: 200px;',
                'placeholder' => 'Seleccionar',
                'requiered' => false,
            ],
        ])
        //fechaPublicacionBoletin
        ->add('fechaPublicacionBoletin',DateType::class,[
            'required' => false,
            'widget' =>'single_text',
            'html5'=>false,
            'format'=> 'dd/MM/yyyy',
            'label' => 'Fecha de publicacion boletin',
            'attr'=>[
                'class' => 'datepicker',
                'style' => 'min-width: 200px;',
                'placeholder' => 'Seleccionar',
                'requiered' => false,
            ],
        ])
        //fechaPromulgacion
        ->add('fechaPromulgacion',DateType::class,[
            'required' => false,
            'widget' =>'single_text',
            'html5'=>false,
            'format'=> 'dd/MM/yyyy',
            'label' => 'Fecha de promulgacion',
            'attr'=>[
                'class' => 'datepicker ',
                'style' => 'min-width: 200px;',
                'placeholder' => 'Seleccionar',
                'requiered' => false,
            ],
        ])
        //resumen
        ->add('resumen')
        //texto
        ->add('texto',  CKEditorType::class,[
            'config' => [
                'conf' => 'basic',
                'config_name' => 'full_config',
                'toolbar' => 'full',
                'pasteFilter' => 'h1 h2 p ul ol li; a[!href]'
                //'uiColor' =>  '#FFFFFF',
                //'removeButtons' => 'Save,NewPage',
                //'extraPlugins' => 'simpleImageUpload',
                //'filebrowserImageBrowseRoute' => '',
                //'filebrowserUploadUrl'=> '/public/uploads',
                //'removePlugins' => 'pasteimage',
                
            ],
            'purify_html' => true,
            'constraints'=>[new NotBlank(),],
            'label'=>'Texto(*)'
        ])
        //items
        ->add('items',EntityType::class,[
            'class' => Item::class,           
            'choices' =>[],
            'multiple' =>true,
            'required' => false,
            'label' => 'Item',
            'label' => 'Item',
        ])
        //archivo
        ->add('archivo', FileType::class,[
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false,
            'attr' => ['class'=>'custom-file-imput'],
        ])
        //nombre_archivo
        ->add('nombre_archivo',TextType::class,[
            'mapped' => false,
            'required' => false,
            'attr'=>['id'=>'ida'],
        ])
        //etiquetas
        ->add('etiquetas',EntityType::class,[
            'required' => false,
            'class' => Etiqueta::class,
                'query_builder' => function(EtiquetaRepository $eti){
                    return $eti->createQueryBuilder('nombre')->orderBy('nombre.nombre','ASC');
                },
                'choice_label' => 'nombre',
                'multiple' => true,
                'attr'=>[
                    'class'=>'js-example-basic-multiple',
                    ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}