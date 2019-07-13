<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFacetsType extends AbstractType
{
    /**
     * @var RegistryInterface
     */
    private $facetRegistry;

    public function __construct(RegistryInterface $facetRegistry)
    {
        $this->facetRegistry = $facetRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['facets'] as $facetId => $facetData) {
            $facet = $this->facetRegistry->getFacetById($facetId);
            $choices = [];
            foreach ($facetData['buckets'] as $bucket) {
                $choices[$facet->getBucketLabel($bucket)] = $bucket['key'];
            }
            $builder
                ->add(
                    $facetId,
                    ChoiceType::class,
                    [
                        'label' => $facetId, // TODO introduce getLabel method to FacetInterface
                        'choices' => $choices,
                        'expanded' => true,
                        'multiple' => true,
                    ]
                )
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('facets');
    }
}
