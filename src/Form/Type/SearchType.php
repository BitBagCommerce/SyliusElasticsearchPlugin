<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use BitBag\SyliusElasticsearchPlugin\Model\SearchBox;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Adapter\AdapterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SearchType extends AbstractType
{
    /** @var PaginatedFinderInterface */
    private $finder;

    /** @var RegistryInterface */
    private $facetRegistry;

    /** @var QueryBuilderInterface */
    private $searchProductsQueryBuilder;

    public function __construct(
        PaginatedFinderInterface $finder,
        RegistryInterface $facetRegistry,
        QueryBuilderInterface $searchProductsQueryBuilder
    ) {
        $this->finder = $finder;
        $this->facetRegistry = $facetRegistry;
        $this->searchProductsQueryBuilder = $searchProductsQueryBuilder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('box', SearchBoxType::class, ['label' => false])
            ->setMethod('GET')
        ;

        $formModifier = function (FormInterface $form, AdapterInterface $adapter) {
            if (!$adapter instanceof FantaPaginatorAdapter) {
                return;
            }

            $form->add('facets', SearchFacetsType::class, ['facets' => $adapter->getAggregations(), 'label' => false]);
        };

        $builder
            ->get('box')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    /** @var SearchBox $data */
                    $data = $event->getForm()->getData();

                    if (!$data->getQuery()) {
                        return;
                    }

                    $query = new Query($this->searchProductsQueryBuilder->buildQuery(['query' => $data->getQuery()]));

                    foreach ($this->facetRegistry->getFacets() as $facetId => $facet) {
                        $query->addAggregation($facet->getAggregation()->setName($facetId));
                    }
                    $query->setSize(0);

                    $results = $this->finder->findPaginated($query);

                    if ($results->getAdapter()) {
                        $formModifier($event->getForm()->getParent(), $results->getAdapter());
                    }
                }
            )
        ;
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
