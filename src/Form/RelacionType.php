<?php

namespace App\Form;

use App\Entity\Norma;
use App\Entity\Relacion;
use App\Entity\TipoRelacion;
use App\Repository\NormaRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RelacionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('norma')
            ->add('complementada',EntityType::class,[
                'class' => Norma::class,
                'query_builder' =>function(NormaRepository $normaRepo){
                    return $normaRepo->createQueryBuilder('norma')->orderBy('norma.titulo','ASC');
                },
                'choice_label' => 'titulo',
                'multiple' => false,
                'required' => true,
                'attr' =>[
                    'class'=>'selectpicker',
                    'data-size'=>'10',
                    'data-live-search'=>true,
                ]
            ])
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
                ])
            //->add('fechaRelacion')
            ->add('descripcion')
            ->add('resumen',TextType::class,[
                'required' => false
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Relacion::class,
        ]);
    }
}
