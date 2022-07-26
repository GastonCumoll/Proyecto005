<?php

namespace App\Form;

use App\Entity\TipoNorma;
use App\Entity\TipoNormaReparticion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoNormaReparticionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tipoNormaId',EntityType::class,[
                'class'=>TipoNorma::class,
                'disabled'=>true,
            ])
            ->add('reparticionId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TipoNormaReparticion::class,
        ]);
    }
}
