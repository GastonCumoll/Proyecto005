<?php

namespace App\Form;

use App\Entity\Norma;
use App\Entity\Etiqueta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtiquetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('normas',EntityType::class,[
                'class' => Norma::class,
                'choice_label' => 'titulo',
                'required' => false,
                'multiple' => true,
                'attr' => [
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
            'data_class' => Etiqueta::class,
        ]);
    }
}
