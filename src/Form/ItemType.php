<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Norma;
use App\Form\ItemType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('padre', EntityType::class, [
                'class' => Item::class,
                'placeholder' => '',
                'required' =>false,
                'multiple'=>false,
                'choice_label' => 'nombre',
                'attr' => [
                    'class'=>'selectpicker1',
                    'data-size'=>'auto',
                    'data-live-search'=>true,
                    'data-max-options'=>1,
                ]
            ])
            // ->add('orden', IntegerType::class,[
            //     'required' =>false,
            // ])
            // //->add('dependencias')
            // ->add('normas',EntityType::class, [
            //     'class' => Norma::class,
            //     'multiple' => true,
            //     'placeholder' => '',
            //     'required' =>false,
            //     'choice_label' => 'titulo',
            //     'attr' => [
            //         'class'=>'selectpicker',
            //         'data-size'=>'10',
            //         'data-live-search'=>true,
            //     ]
            // ]) 
        ;
        
            }
    //     $formModifier = function (FormInterface $form, Item $item = null) {
    //         $dependencias = null === $item ? [] : $item->getDependencias();
            
    //         $form->add('dependencias', EntityType::class, [
    //             'class' => Item::class,
    //             'choices' => $dependencias,
    //             'multiple' =>true,
    //             'required' =>false,
    //         ]);
    //     };

    //     $builder->addEventListener(
    //         FormEvents::PRE_SET_DATA,
    //         function (FormEvent $event) use ($formModifier) {

    //             // this would be your entity, i.e. SportMeetup
    //             $data = $event->getData();
    //             //dd($data);
    //             $formModifier($event->getForm(), $data);
                
    //         }
    //     );
    //     $builder->get('padre')->addEventListener(
    //         FormEvents::POST_SUBMIT,
    //         function (FormEvent $event) use ($formModifier) {
    //             // It's important here to fetch $event->getForm()->getData(), as
    //             // $event->getData() will get you the client data (that is, the ID)
    //             $padre = $event->getForm()->getData();
    //             // dd($padre->getNombre());
    //             // since we've added the listener to the child, we'll have to pass on
    //             // the parent to the callback functions!
    //             if($padre != null){
    //                 $dep=$padre->getDependencias();
    //             }
    //             $formModifier($event->getForm()->getParent(), $padre);
    //         }
    //     );
        
    // }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
