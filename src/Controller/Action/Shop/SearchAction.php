<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Block\SearchFormEventListener;
use Elastica\Query\MultiMatch;
use FOS\ElasticaBundle\Finder\FinderInterface;
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
     * @var FinderInterface
     */
    private $finder;
    /**
     * @var SearchFormEventListener
     */
    private $searchFormEventListener;

    public function __construct(
        EngineInterface $templatingEngine,
        FinderInterface $finder,
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

        $results = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $query = new MultiMatch();
            $query->setQuery($data['query']);
            // TODO set search fields here (pay attention to locale-contex field, like name): $query->setFields([]);
            $query->setFuzziness('AUTO');

            $results = $this->finder->find($query);
        }
        return $this->templatingEngine->renderResponse($template, ['results' => $results]);
    }
}
