<?php

namespace App\Form;

use App\Entity\Relacion;
use App\Entity\TipoRelacion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RelacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fechaRelacion')
            ->add('descripcion')
            ->add('resumen')
            ->add('usuario')
            ->add('norma')
            ->add('complementada')
            ->add('tipoRelacion',EntityType::class,[
                'class' => TipoRelacion::class,
                'placeholder' => '',
                'choice_filter' => ChoiceList::filter(
                    $this,
                        function ($tipoRelacion) {
                            if ($tipoRelacion instanceof TipoRelacion) {
                                return $tipoRelacion->getPrioridad()==1;
                            }
                        return false;
                    },
                    'tipoRelacion'
                )
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Relacion::class,
        ]);
    }
}
