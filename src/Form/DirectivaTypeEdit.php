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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class DirectivaTypeEdit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $tipoNormasUsuario = $options['tipoNormasUsuario'];
        
        $tnuChoice = [];
        foreach ($tipoNormasUsuario as $t){
            $id = str_pad($t['idTipoNorma'], 1, "0", STR_PAD_LEFT);
            $tnuChoice[$t['nombreTipoNorma']] = $t['idTipoNorma'];
        }

        $builder
        ->add('tipoDeNorma',ChoiceType::class,[
            'choices' => $tnuChoice,
            'multiple' =>false,
            'mapped'=>false,
            'required' => false,
            'attr' => [
                'class'=>'selectpicker',
            ]
        ])
        ->add('numeroAuxiliar',NumberType::class,[
            'label' => 'Número',
            'help' => 'Solo números',
            'required' => false,
        ]
        )
        ->add('titulo',TextType::class,[
            'label'=> 'Titulo (*)',
            'required' => false,
            'constraints'=>[new NotBlank(),],
        ])
        ->add('fechaSancion',DateType::class,[
            'required' => false,
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
        ->add('fechaPublicacionBoletin',DateType::class,[
            'required' => false,
            'widget' =>'single_text',
            'html5'=>false,
            'format'=> 'dd/MM/yyyy',
            'label' => 'Fecha de publicacion boletin',
            'attr'=>[
                'class' => 'datepicker col-2',
                'style' => 'min-width: 200px;',
                'placeholder' => 'Seleccionar',
                'requiered' => false,
            ],
        ])
        ->add('fechaPromulgacion',DateType::class,[
            'required' => false,
            'widget' =>'single_text',
            'html5'=>false,
            'format'=> 'dd/MM/yyyy',
            'label' => 'Fecha de promulgacion',
            'attr'=>[
                'class' => 'datepicker col-2',
                'style' => 'min-width: 200px;',
                'placeholder' => 'Seleccionar',
                'requiered' => false,
            ],
        ])
        ->add('resumen')
        ->add('texto',  CKEditorType::class,[
            'config' => [
                'conf' => 'basic',
                'config_name' => 'full_config',
                'toolbar' => 'full',
                'pasteFilter' => 'h1 h2 p ul ol li; a[!href]'
            ],
            'purify_html' => true,
            'constraints'=>[new NotBlank(),],
            'label'=>'Texto(*)'
        ])
        ->add('items',EntityType::class,[
            'class' => Item::class,           
            'choices' =>[],
            'multiple' =>true,
            'required' => false,
            'label' => 'Item',
            ])
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
            'tipoNormasUsuario' => NULL,
        ]);
    }
}