<?php

namespace App\Form;

use App\Entity\TipoNorma;
use App\Entity\TipoNormaRol;
use App\Service\SeguridadService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TipoNormaRolType extends AbstractType
{
    private $seguridad;
    public function __construct(SeguridadService $seguridad){
        $this->seguridad=$seguridad;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$roles es la lista de roles del sistema
        $roles=$this->seguridad->getListaRolesDeSistemaAction(114);
        $roles=json_decode($roles, true);
        //dd($roles);
        $choices=[];
        foreach ($roles as $rol) {
            $choices[$rol['id']]=$rol['id'];
        }
        //dd($choices);
        
        //dd($choices);
        $builder
            ->add('tipoNorma',EntityType::class,[
                'class' => TipoNorma::class,
                'disabled' => true,
            ])
            ->add('nombreRol',ChoiceType::class,[
                'choices' => $choices,
            ])
            ;
            

        

        }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TipoNormaRol::class,
        ]);
    }
}
