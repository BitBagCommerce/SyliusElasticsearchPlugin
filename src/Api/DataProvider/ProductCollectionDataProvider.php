<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Api\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use BitBag\SyliusElasticsearchPlugin\Api\RequestDataHandler\RequestDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Api\Resolver\FacetsResolverInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinderInterface;

final class ProductCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private const SUPPORTED_OPERATION_TYPE = 'elasticsearch_shop_get';

    public function __construct(
        private RequestDataHandlerInterface $dataHandler,
        private ShopProductsFinderInterface $shopProductsFinder,
        private FacetsResolverInterface $facetsResolver
    ) {
    }

    public function getCollection(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ) {
        $data = $this->dataHandler->retrieveData($context);
        $facets = $this->facetsResolver->resolve($data);
        $products = $this->shopProductsFinder->find($data);

        return [
            'items' => $products->jsonSerialize(),
            'facets' => $facets,
            'pagination' => [
                'current_page' => $products->getCurrentPage(),
                'has_previous_page' => $products->hasPreviousPage(),
                'has_next_page' => $products->hasNextPage(),
                'per_page' => $products->getMaxPerPage(),
                'total_items' => $products->getNbResults(),
                'total_pages' => $products->getNbPages(),
            ],
        ];
    }

    public function supports(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): bool {
        return self::SUPPORTED_OPERATION_TYPE === $operationName;
    }
}
