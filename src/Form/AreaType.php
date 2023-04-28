<?php

namespace App\Form;

use App\Entity\Area;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AreaType extends AbstractType
{
    private $reparticiones;

    public function __construct($reparticiones = null)
    {
        $this->reparticiones = $reparticiones;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $reparticiones = $options['reparticiones'];
        
        $reparticionChoice = [];
        foreach ($reparticiones as $r){
            $id = str_pad($r['idReparticion'], 3, "0", STR_PAD_LEFT);
            $reparticionChoice[$id." - ".$r['nombre']] = $r['idReparticion'];
        }
        $builder
            ->add('nombre', ChoiceType::class, [
                'choices' => $reparticionChoice,
                'required'=>true,
                'attr'=> [
                    'class'=>'selectpicker',
                    'data-size'=>'10',
                    'data-live-search'=>true,
                    
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'reparticiones' => NULL,
        ]);
    }
}
