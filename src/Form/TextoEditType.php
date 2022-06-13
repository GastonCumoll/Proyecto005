<?php

namespace App\Form;

use App\Entity\Norma;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use KMS\FroalaEditorBundle\Form\Type\FroalaEditorType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class TextoEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('texto',  CKEditorType::class,[
            'config' => [
                'conf' => 'default',
                'config_name' => 'basic_config',
                //'toolbar' => 'standard',
                //'uiColor' =>  '#FFFFFF',
                //'removeButtons' => 'Save,NewPage',
                //'extraPlugins' => 'simpleImageUpload',
                //'filebrowserImageBrowseRoute' => '',
                //'filebrowserUploadUrl'=> '/public/uploads',
                //'removePlugins' => 'pasteimage',
                'pasteFilter' => 'h1 h2 p ul ol li; img[!src, alt]; a[!href]',
            ],
            'purify_html' => true,
        ]);

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Norma::class,
        ]);
    }
    
}