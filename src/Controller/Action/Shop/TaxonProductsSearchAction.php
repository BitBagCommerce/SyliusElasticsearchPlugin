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

use BitBag\SyliusElasticsearchPlugin\Form\Type\ShopProductsFilterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class TaxonProductsSearchAction extends AbstractSearchAction
{
    public function __invoke(Request $request): Response
    {
        $template = $request->get('template');
        $form = $this->formFactory->create(ShopProductsFilterType::class);
        $form->handleRequest($request);

        $requestData = array_merge(
            $form->getData(),
            $request->query->all(),
            ['slug' => $request->get('slug')]
        );

        if ($form->isSubmitted() && !$form->isValid()) {
            $requestData = $this->clearInvalidEntries($form, $requestData);
        }

        $data = $this->dataHandler->retrieveData($requestData);
        $products = $this->finder->find($data);

        return new Response($this->twig->render($template, [
            'form' => $form->createView(),
            'products' => $products,
            'taxon' => $data['taxon'] ?? null,
        ]));
    }
}
