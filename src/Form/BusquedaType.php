<?php

namespace App\Form;
use App\Entity\Norma;
use App\Entity\Etiqueta;
use App\Entity\TipoNorma;
use App\Repository\EtiquetaRepository;
use App\Repository\TipoNormaRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;



class BusquedaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo',TextType::class,[
                'required' => false,
                'mapped' => false
            ])
            ->add('tipo',EntityType::class,[
                'required' => false,
                'class' => TipoNorma::class,
                'query_builder' => function(TipoNormaRepository $tnRep){
                    return $tnRep->createQueryBuilder('nombre')->orderBy('nombre.nombre','ASC');
                },
                'choice_label' => 'nombre',
                'multiple' => false
                ])
            ->add('numero',NumberType::class,[
                'required' => false,
                'mapped' => false,
                'label' => 'hola'
                ])
            ->add('anio',TextType::class,[
                'required' => false,
                'mapped' => false,
                'label' => 'Año'
                ])
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
                ->add('texto',TextType::class,[
                    'required' => false,
                    'mapped' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        // $resolver->setDefaults([
        //     'data_class' => Etiqueta::class,
        // ]);
    }
}
