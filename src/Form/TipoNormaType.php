<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\TipoNorma;
use App\Repository\AreaRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoNormaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            // ->add('area',EntityType::class,[
            //     'required' => false,
            //     'class' => Area::class,
            //         'query_builder' => function(AreaRepository $area){
            //             return $area->createQueryBuilder('nombre')->orderBy('nombre.nombre','ASC');
            //         },
            //         'choice_label' => 'nombre',
            //         'multiple' => false,
            //         'attr'=>[
            //             'class'=>'js-example-basic-multiple',
            //             ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TipoNorma::class,
        ]);
    }
}
