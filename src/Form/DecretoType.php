<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Norma;
use App\Form\ItemType;
use App\Entity\Etiqueta;
use App\Form\Type\Select2Type;
use App\Form\Type\TagsInputType;
use App\Repository\ItemRepository;
use App\Repository\EtiquetaRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
        ->add('titulo')
        ->add('fechaSancion',DateType::class,[
            'widget' =>'single_text',
            'html5'=>false,
            'format'=> 'dd/MM/yyyy',
            'label' => 'Fecha de sancion',
            'attr'=>[
                'class' => 'datepicker col-2',
                'style' => 'min-width: 200px;',
                'placeholder' => 'Seleccionar',
                'requiered' => false,
            ],
        ])
        //->add('fechaPublicacion')
        ->add('resumen')
        ->add('texto',  CKEditorType::class,[
            'config' => [
                'conf' => 'default',
                'config_name' => 'basic_config',
                //'toolbar' => 'standard',
                //'uiColor' =>  '#FFFFFF',
                //'removeButtons' => 'Save,NewPage',
                //'extraPlugins' => 'simpleImageUpload',
                //'filebrowserImageBrowseRoute' => '',
                //'filebrowserUploadUrl'=> '/public/uploads',
                //'removePlugins' => 'pasteimage',
                'pasteFilter' => 'h1 h2 p ul ol li',
            ],
            'purify_html' => true,
        ])
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
            ])
        ->add('archivo', FileType::class,[
            'multiple'=>true,
            'mapped'=>false,
            'required'=>false,
            'attr' => ['class'=>'custom-file-imput'],
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
                'attr'=>[
                    'class'=>'js-example-basic-multiple',
                    ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}