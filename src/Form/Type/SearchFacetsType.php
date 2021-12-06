<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Model\SearchFacets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SearchFacetsType extends AbstractType
{
    /** @var RegistryInterface */
    private $facetRegistry;

    public function __construct(RegistryInterface $facetRegistry)
    {
        $this->facetRegistry = $facetRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['facets'] as $facetId => $facetData) {
            $facet = $this->facetRegistry->getFacetById($facetId);
            $choices = [];
            foreach ($facetData['buckets'] as $bucket) {
                $choices[$facet->getBucketLabel($bucket)] = $bucket['key'];
            }
            if (!empty($choices)) {
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
        $resolver->setDefault('data_class', SearchFacets::class);
    }
}
