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

use BitBag\SyliusElasticsearchPlugin\Facet\AutoDiscoverRegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Form\EventSubscriber\AddFacetsEventSubscriber;
use BitBag\SyliusElasticsearchPlugin\Form\Resolver\ProductsFilterFacetResolverInterface;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SearchType extends AbstractType
{
    public function __construct(
        private AutoDiscoverRegistryInterface $autoDiscoverRegistry,
        private ProductsFilterFacetResolverInterface $facetsResolver
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('box', SearchBoxType::class, ['label' => false])
            ->setMethod('GET')
        ;

        $builder->addEventSubscriber(new AddFacetsEventSubscriber(
            $this->autoDiscoverRegistry,
            $this->facetsResolver
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'bitbag_elasticsearch_search';
    }
}
