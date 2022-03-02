<?php

namespace App\Form;

use App\Entity\Norma;
use App\Form\NormaType;
use App\Entity\Relacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RelacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('fechaRelacion')
            ->add('descripcion')
            ->add('resumen')
            ->add('usuario')
            //->add('norma')
            ->add('complementada'
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Relacion::class,
        ]);
    }
}
