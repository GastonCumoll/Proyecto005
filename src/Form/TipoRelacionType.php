<?php

namespace App\Form;

use App\Entity\TipoRelacion;
use Symfony\Component\Form\AbstractType;
use App\Repository\TipoRelacionRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoRelacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('inverso',EntityType::class,[
                'required' => false,
                'class' => TipoRelacion::class,
                'query_builder' => function(TipoRelacionRepository $t){
                    return $t->createQueryBuilder('t')->where('t.prioridad = 1')->andWhere('t.inverso = 0')->orderBy('t.nombre','ASC');
                },
                'choice_label' => 'nombre',
                'multiple' => false
                ])
            // ->add('prioridad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TipoRelacion::class,
        ]);
    }
}
