<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class Select2Type extends AbstractType {

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];
        $class = isset($attr['class']) ? $attr['class'].' ' : '';
        $class .= 'select2';
        $attr['class'] = $class;
        $attr['data-autocomplete-url'] = $attr['data-autocomplete-url'];
        $view->vars['attr'] = $attr;
    }

    public function getParent() {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver) { 
        $resolver->setDefaults([
            'attr' => [
                'class' => 'select2',
                'data-autocomplete-url' => ''
            ]
        ]);
    }
}