<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop;

use BitBag\SyliusElasticsearchPlugin\Form\Type\SearchType;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use BitBag\SyliusElasticsearchPlugin\Model\SearchBox;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SiteWideProductsSearchAction extends AbstractSearchAction
{
    public function __invoke(Request $request): Response
    {
        $template = $request->get('template', '@BitBagSyliusElasticsearchPlugin/Shop/search.html.twig');
        $form = $this->formFactory->create(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var Search $search */
            $search = $form->getData();

            /** @var SearchBox $searchBox */
            $searchBox = $search->getBox();

            $data = array_merge(
                ['query' => $searchBox->getQuery()],
                ['facets' => $search->getFacets()],
                $this->dataHandler->retrieveData($request->query->all()),
            );

            if (!$form->isValid()) {
                $data = $this->clearInvalidEntries($form, $data);
            }

            $results = $this->finder->find($data);
        }

        return new Response($this->twig->render(
            $template,
            [
                'results' => $results ?? null,
                'searchForm' => $form->createView(),
            ]
        ));
    }
}
