<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Block\SearchFormEventListener;
use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchFacetsType;
use BitBag\SyliusElasticsearchPlugin\Model\FacetsConfig;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use Elastica\Aggregation\Histogram;
use Elastica\Aggregation\Range;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\MultiMatch;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\FormFactoryInterface;
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

    public function __construct(
        EngineInterface $templatingEngine,
        PaginatedFinderInterface $finder,
        SearchFormEventListener $searchFormEventListener
    ) {
        $this->templatingEngine = $templatingEngine;
        $this->finder = $finder;
        $this->searchFormEventListener = $searchFormEventListener;
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
                foreach ($search->getFacets() as $key => $selectedOptions) {
                    if (!$selectedOptions) {
                        continue;
                    }
                    $facetsConfig = FacetsConfig::get();
                    if (!array_key_exists($key, $facetsConfig)) {
                        throw new \RuntimeException("Unkown configuration for facet with key '$key'.");
                    }
                    $facetConfig = $facetsConfig[$key];
                    $facetType = $facetConfig['type'];
                    if ($facetType === 'histogram') {
                        $rangeBoolQuery = new Query\BoolQuery();
                        foreach ($selectedOptions as $selectedHistogram) {
                            $rangeQuery = new Query\Range();
                            $rangeQuery->addField(
                                $facetConfig['options']['field'],
                                [
                                    'gte' => $selectedHistogram,
                                    'lte' => $selectedHistogram + $facetConfig['options']['interval']
                                ]
                            );
                            $rangeBoolQuery->addShould($rangeQuery);
                        }
                        $boolQuery->addFilter($rangeBoolQuery);
                    } elseif ($facetType === 'terms') {
                        $boolQuery->addFilter(new Query\Terms($facetConfig['options']['field'], $selectedOptions));
                    } else {
                        throw new \RuntimeException("Unknown facet type '{$facetType}'.");
                    }
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
