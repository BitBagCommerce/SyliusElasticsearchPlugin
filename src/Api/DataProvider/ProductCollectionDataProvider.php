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

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use BitBag\SyliusElasticsearchPlugin\Api\RequestDataHandler\RequestDataHandlerInterface;
use BitBag\SyliusElasticsearchPlugin\Api\Resolver\FacetsResolverInterface;
use BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinderInterface;
use Webmozart\Assert\Assert;

final class ProductCollectionDataProvider implements ProviderInterface
{
    public function __construct(
        private RequestDataHandlerInterface $dataHandler,
        private ShopProductsFinderInterface $shopProductsFinder,
        private FacetsResolverInterface $facetsResolver
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        Assert::isInstanceOf($operation, GetCollection::class);

        $data = $this->dataHandler->retrieveData($context);

        $facets = $this->facetsResolver->resolve($data);

        $products = $this->shopProductsFinder->find($data);

        /** @var array $result */
        $result =  [
            'items' => iterator_to_array($products->getCurrentPageResults()),
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

        return $result;
    }
}