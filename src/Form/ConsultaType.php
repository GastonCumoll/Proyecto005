<?php

namespace App\Form;

use App\Entity\Consulta;
use App\Entity\TipoConsulta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('email')
            ->add('numeroTel')
            ->add('texto')
            ->add('tipoConsulta',EntityType::class,[
                'class' => TipoConsulta::class,
                'multiple' =>true,
            'required' => false,
            'choice_label' => 'nombre',
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
            'data_class' => Consulta::class,
        ]);
    }
}
