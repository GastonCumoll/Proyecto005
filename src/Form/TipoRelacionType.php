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
    private $idBase;

    public function __construct($idBase = null)
    {
        $this->idBase = $idBase;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $idBase = $options['id'];
        $choices[]=$idBase;
        $builder
            ->add('nombre')
            ->add('inverso',EntityType::class,[
                'required' => false,
                'class' => TipoRelacion::class,
                'choices'=>$choices,
                // 'query_builder' => function(TipoRelacionRepository $t){
                    
                //     // return $t->createQueryBuilder('t')->where('t.prioridad = 1')->andWhere('t.inverso = b')->setParameter('b',$idBase)->orderBy('t.nombre','ASC');
                //     return $t->findOneById($idBase);
                // },
                'choice_label' => 'nombre',
                'multiple' => false
                ])
            // ->add('prioridad')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'id'=> NULL,
        ]);
    }
}
