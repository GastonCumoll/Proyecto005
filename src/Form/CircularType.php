<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Norma;
use App\Entity\Etiqueta;
use App\Form\CircularType;
use App\Repository\EtiquetaRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CircularType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero')
            ->add('titulo')
            ->add('archivo', FileType::class,[
                'multiple'=>true,
                'mapped'=>false,
                'required'=>false,
                'attr' => ['class'=>'custom-file-imput'],
            ])
            //->add('fechaSancion')
            //->add('fechaPublicacion')
            
            ->add('resumen')
            ->add('texto',  CKEditorType::class,[
                'config' => [
                    'conf' => 'default',
                    'config_name' => 'basic_config',
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
                    'attr'=>[
                        'class'=>'js-example-basic-multiple',
                        ]
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
            ])
            // ->add('rela', CheckboxType::class, array(
            //     'required' => false,
            //     'value' => 1,
            //     'label' => '¿Está relacionada con otra norma?'
            // ))
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