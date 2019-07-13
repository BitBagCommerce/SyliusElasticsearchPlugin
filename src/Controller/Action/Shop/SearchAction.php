<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Block\SearchFormEventListener;
use BitBag\SyliusElasticsearchPlugin\Facet\RegistryInterface;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use Elastica\Query;
use Elastica\Query\MultiMatch;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SearchAction
{
    /**
     * @var EngineInterface
     */
    private $templatingEngine;
    /**
     * @var PaginatedFinderInterface
     */
    private $finder;
    /**
     * @var SearchFormEventListener
     */
    private $searchFormEventListener;
    /**
     * @var RegistryInterface
     */
    private $facetRegistry;

    public function __construct(
        EngineInterface $templatingEngine,
        PaginatedFinderInterface $finder,
        SearchFormEventListener $searchFormEventListener,
        RegistryInterface $facetRegistry
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->finder = $finder;
        $this->searchFormEventListener = $searchFormEventListener;
        $this->facetRegistry = $facetRegistry;
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

            // TODO this is duplicated from src/Form/Type/SearchType.php:61
            $multiMatch = new MultiMatch();
            $multiMatch->setQuery($search->getBox()->getQuery());
            // TODO set search fields here (pay attention to locale-contex field, like name): $query->setFields([]);
            $multiMatch->setFuzziness('AUTO');

            $boolQuery = new Query\BoolQuery();
            $boolQuery->addMust($multiMatch);

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
        }
        return $this->templatingEngine->renderResponse(
            $template,
            ['results' => $results, 'searchForm' => $form->createView()]
        );
    }
}
