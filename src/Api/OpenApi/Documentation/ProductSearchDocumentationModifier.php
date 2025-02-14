<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Api\OpenApi\Documentation;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\Parameter;
use ApiPlatform\OpenApi\OpenApi;

final class ProductSearchDocumentationModifier implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        $paths = $openApi->getPaths();
        $pathItem = $paths->getPath('/api/v2/shop/products/search');

        if (null === $pathItem) {
            return $openApi;
        }

        $operation = $pathItem->getGet();
        if (null === $operation) {
            return $openApi;
        }

        $parameters = [
            new Parameter(
                'query',
                'query',
                'Search query string',
                required: false,
                deprecated: false,
                allowEmptyValue: false,
                schema: ['type' => 'string'],
                example: 'shoes'
            ),

            new Parameter(
                'limit',
                'query',
                'Number of items per page',
                required: false,
                deprecated: false,
                allowEmptyValue: false,
                schema: ['type' => 'integer', 'default' => 10],
                example: 20
            ),

            new Parameter(
                'page',
                'query',
                'Page number',
                required: false,
                deprecated: false,
                allowEmptyValue: false,
                schema: ['type' => 'integer', 'default' => 1],
                example: 2
            ),

            new Parameter(
                'order_by',
                'query',
                'Sort field',
                required: false,
                deprecated: false,
                allowEmptyValue: false,
                schema: ['type' => 'string', 'enum' => ['sold_units', 'product_created_at', 'price']],
                example: 'price'
            ),

            new Parameter(
                'sort',
                'query',
                'Sort direction',
                required: false,
                deprecated: false,
                allowEmptyValue: false,
                schema: ['type' => 'string', 'enum' => ['asc', 'desc']],
                example: 'asc'
            ),

            new Parameter(
                'facets',
                'query',
                'Filter facets with dynamic keys',
                required: false,
                deprecated: false,
                allowEmptyValue: false,
                schema: ['type' => 'object'],
                example: '{"t_shirt_material": ["100% cotton"], "t_shirt_brand": ["modern_wear"]}'
            ),
        ];

        $newOperation = $operation->withParameters($parameters);
        $paths->addPath('/api/v2/shop/products/search', $pathItem->withGet($newOperation));

        return $openApi->withPaths($paths);
    }
}
