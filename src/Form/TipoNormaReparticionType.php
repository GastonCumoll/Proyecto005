<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\TipoNorma;
use App\Repository\AreaRepository;
use App\Entity\TipoNormaReparticion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TipoNormaReparticionType extends AbstractType
{
    private $reparticiones;

    public function __contrusct($reparticiones = NULL){
        $this->reparticiones = $reparticiones;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $reparticiones = $options['reparticiones'];
        $reparticionesChoices=[];
        //dd($reparticiones);
        foreach ($reparticiones as $unaRepa) {
            //'reparticion' => id
            $reparticionesChoices[$unaRepa->getNombre()]=$unaRepa;
        }
        //dd($reparticionesChoices);
        $builder
            ->add('tipoNormaId',EntityType::class,[
                'class'=>TipoNorma::class,
                'disabled'=>true,
            ])
            ->add('reparticionId',ChoiceType::class,[
                'label' => 'Reparticion ',
                'choices' => $reparticionesChoices,
            ])
            // ->add('reparticionId',EntityType::class,[
            //     'class'=> Area::class,
            //     'query_builder'=>function(AreaRepository $a){
            //         return $a->createQueryBuilder('nombre')->orderBy('nombre.nombre','ASC');
            //     },
            //     'choice_label' => 'nombre',
            //     'multiple' => false,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TipoNormaReparticion::class,
            'reparticiones' => NULL,
        ]);
    }
}
