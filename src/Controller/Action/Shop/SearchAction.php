<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Block\SearchFormEventListener;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class SearchAction
{
    /** @var Environment */
    private $twig;

    /** @var PaginatedFinderInterface */
    private $finder;

    /** @var SearchFormEventListener */
    private $searchFormEventListener;

    /** @var RegistryInterface */
    private $facetRegistry;

    /** @var QueryBuilderInterface */
    private $searchProductsQueryBuilder;

    /** @var PaginationDataHandlerInterface */
    private $paginationDataHandler;

    public function __construct(
        Environment $twig,
        PaginatedFinderInterface $finder,
        SearchFormEventListener $searchFormEventListener,
        RegistryInterface $facetRegistry,
        QueryBuilderInterface $searchProductsQueryBuilder,
        PaginationDataHandlerInterface $paginationDataHandler
    ) {
        $this->twig = $twig;
        $this->finder = $finder;
        $this->searchFormEventListener = $searchFormEventListener;
        $this->facetRegistry = $facetRegistry;
        $this->searchProductsQueryBuilder = $searchProductsQueryBuilder;
        $this->paginationDataHandler = $paginationDataHandler;
    }

    public function __invoke(Request $request): Response
    {
        $template = $request->get('template');
        $form = $this->searchFormEventListener->getForm();
        $form->handleRequest($request);

        $results = null;
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Search $search */
            $search = $form->getData();

            $boolQuery = new Query\BoolQuery();
            $boolQuery->addMust(
                $this->searchProductsQueryBuilder->buildQuery(['query' => $search->getBox()->getQuery()])
            );

            if ($search->getFacets()) {
                foreach ($search->getFacets() as $facetId => $selectedBuckets) {
                    if (!$selectedBuckets) {
                        continue;
                    }
                    $facet = $this->facetRegistry->getFacetById($facetId);
                    $boolQuery->addFilter($facet->getQuery($selectedBuckets));
                }
            }

            $query = new Query($boolQuery);

            $results = $this->finder->findPaginated($query);
            $paginationData = $this->paginationDataHandler->retrieveData($request->query->all());
            $results->setCurrentPage($paginationData[PaginationDataHandlerInterface::PAGE_INDEX]);
            $results->setMaxPerPage($paginationData[PaginationDataHandlerInterface::LIMIT_INDEX]);
        }

        return new Response($this->twig->render(
            $template,
            ['results' => $results, 'searchForm' => $form->createView()]
        ));
    }
}
