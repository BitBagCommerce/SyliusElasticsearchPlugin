<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchBoxType;
use Elastica\Query\MultiMatch;
use FOS\ElasticaBundle\Finder\FinderInterface;
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
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var FinderInterface
     */
    private $finder;

    public function __construct(EngineInterface $templatingEngine, FormFactoryInterface $formFactory, FinderInterface $finder)
    {
        $this->templatingEngine = $templatingEngine;
        $this->formFactory = $formFactory;
        $this->finder = $finder;
    }

    public function __invoke(Request $request): Response
    {
        $template = $request->get('template');
        $defaultData = ['query' => ''];
        $form = $this->formFactory->createBuilder(SearchBoxType::class, $defaultData)->getForm();

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
        return $this->templatingEngine->renderResponse(
            $template,
            ['search_form' => $form->createView(), 'results' => $results]
        );
    }
}
