<?php

namespace App\Form;
use App\Entity\Norma;

use App\Entity\Etiqueta;
use App\Repository\EtiquetaRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;



class BusquedaType extends AbstractType
{
    // private $etiquetas;

    // public function __construct($etiquetas = NULL) {
    //     $this->etiquetas = $etiquetas;
    // }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // $etiquetas = $options['etiquetas']; // $etiquetas es un array
        // $etiquetasChoices = array();
        // foreach($etiquetas as $etiqueta) {
        //     // Sintaxis para el ChoiceType:
        //     // 'Secretaría' => ID
        //     $etiquetasChoices[$etiqueta['nombre']] = $etiqueta['idEtiqueta'];
        // }
        $builder
            ->add('titulo',TextType::class,[
                'required' => false,
                'mapped' => false
            ])
            ->add('tipo',TextType::class,[
                'required' => false,
                'mapped' => false
                ])
            ->add('numero',TextType::class,[
                'required' => false,
                'mapped' => false
                ])
            ->add('anio',TextType::class,[
                
                'required' => false,
                'mapped' => false,
                'label' => 'Año'
                ])
            ->add('etiquetas',EntityType::class,[
                'class' => Etiqueta::class,
                'required' => false,
                
                'query_builder' => function(EtiquetaRepository $etiquetaRepository){
                    return $etiquetaRepository->createQueryBuilder('nombre')->orderBy('nombre.nombre', 'ASC');
                },
                'choice_label' => 'nombre',
                'multiple' => true,
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
