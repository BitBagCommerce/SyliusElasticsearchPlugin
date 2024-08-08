<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Api;

use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Item;
use BitBag\SyliusElasticsearchPlugin\Controller\Response\ItemsResponse;
use BitBag\SyliusElasticsearchPlugin\Finder\NamedProductsFinderInterface;
use BitBag\SyliusElasticsearchPlugin\Transformer\Product\TransformerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListProductsByPartialNameAction
{
    public function __construct(
        private NamedProductsFinderInterface $namedProductsFinder,
        private TransformerInterface $productSlugTransformer,
        private TransformerInterface $productChannelPriceTransformer,
        private TransformerInterface $productImageTransformer
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $itemsResponse = ItemsResponse::createEmpty();

        if (null === $request->query->get('query')) {
            return new JsonResponse($itemsResponse->toArray());
        }

        /** @var array $products */
        $products = $this->namedProductsFinder->findByNamePart((string) $request->query->get('query'));

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            if (null === $productMainTaxon = $product->getMainTaxon()) {
                continue;
            }

            $itemsResponse->addItem(new Item(
                (string) $productMainTaxon->getName(),
                (string) $product->getName(),
                $product->getShortDescription(),
                (string) $this->productSlugTransformer->transform($product),
                $this->productChannelPriceTransformer->transform($product),
                $this->productImageTransformer->transform($product)
            ));
        }

        return new JsonResponse($itemsResponse->toArray());
    }
}
