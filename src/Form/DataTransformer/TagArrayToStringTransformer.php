<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Form\DataTransformer;

use App\Entity\Etiqueta;
use App\Repository\EtiquetaRepository;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;
use Psr\Log\LoggerInterface;

/**
 * This data transformer is used to translate the array of tags into a comma separated format
 * that can be displayed and managed by Bootstrap-tagsinput js plugin (and back on submit).
 *
 * See https://symfony.com/doc/current/form/data_transformers.html
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 * @author Jonathan Boyer <contact@grafikart.fr>
 */
class TagArrayToStringTransformer implements DataTransformerInterface
{
    private $etiquetas;

    public function __construct(EtiquetaRepository $etiquetas)
    {
        $this->etiquetas = $etiquetas->findAll();
        //
    //dd($this->etiquetas);
    }
    // public function log(LoggerInterface $logger): string
    // {
    //     $this->logger->info('I love Tony Vairelles\' hairdresser.');
    // }
    /**
     * {@inheritdoc}
     */
    public function transform($etiquetas): string
    {
        // The value received is an array of Tag objects generated with
        // Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::transform()
        // The value returned is a string that concatenates the string representation of those objects
        /* @var Etiqueta[] $etiquetas */
        //dd($etiquetas);
        return implode(',', $etiquetas);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($string): array
    {
        if (null === $string || u($string)->isEmpty()) {
            return [];
        }

        $names = array_filter(array_unique(array_map('trim', u($string)->split(','))));

        // Get the current tags and find the new ones that should be created.
        $etiquetas = $this->etiquetas->findBy([
            'nombre' => $names,
        ]);
        //dd($etiquetas);
        $newNames = array_diff($names, $etiquetas);
        foreach ($newNames as $name) {
            $etiqueta = new Etiqueta();
            $etiqueta->setNombre($name);
            $etiquetas[] = $etiqueta;
            // There's no need to persist these new tags because Doctrine does that automatically
            // thanks to the cascade={"persist"} option in the App\Entity\Post::$tags property.
        }

        // Return an array of tags to transform them back into a Doctrine Collection.
        // See Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer::reverseTransform()
        return $etiquetas;
    }
}
