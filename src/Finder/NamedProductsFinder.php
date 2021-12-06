<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use FOS\ElasticaBundle\Finder\FinderInterface;

final class NamedProductsFinder implements NamedProductsFinderInterface
{
    /** @var QueryBuilderInterface */
    private $productsByPartialNameQueryBuilder;

    /** @var FinderInterface */
    private $productsFinder;

    public function __construct(
        QueryBuilderInterface $productsByPartialNameQueryBuilder,
        FinderInterface $productsFinder
    ) {
        $this->productsByPartialNameQueryBuilder = $productsByPartialNameQueryBuilder;
        $this->productsFinder = $productsFinder;
    }

    public function findByNamePart(string $namePart): ?array
    {
        $data = ['name' => $namePart];
        $query = $this->productsByPartialNameQueryBuilder->buildQuery($data);

        return $this->productsFinder->find($query);
    }
}
