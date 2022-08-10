<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Norma;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('items',EntityType::class,[
                    'class' => Item::class,
                    'multiple' =>true,
                    'required' => false,
                    'choice_label' => 'nombre',
                    'attr'=> [
                        'class'=>'selectpicker',
                        'data-size'=>'10',
                        'data-live-search'=>true,
                    ]
                    ]);
            }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
}