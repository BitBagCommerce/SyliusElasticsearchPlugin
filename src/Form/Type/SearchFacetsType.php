<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SearchFacetsType extends AbstractType
{
    public function __construct(
        private RegistryInterface $facetRegistry
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['facets'] as $facetId => $facetData) {
            $facet = $this->facetRegistry->getFacetById($facetId);
            $choices = [];
            foreach ($facetData['buckets'] as $bucket) {
                $choices[$facet->getBucketLabel($bucket)] = $bucket['key'];
            }
            if ([] !== $choices) {
                $builder
                    ->add(
                        $facetId,
                        ChoiceType::class,
                        [
                            'label' => $facet->getLabel(),
                            'choices' => $choices,
                            'expanded' => true,
                            'multiple' => true,
                        ]
                    )
                ;
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('facets');
    }
}
