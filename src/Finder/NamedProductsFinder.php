<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Finder;

use BitBag\SyliusElasticsearchPlugin\QueryBuilder\QueryBuilderInterface;
use FOS\ElasticaBundle\Finder\FinderInterface;

final class NamedProductsFinder implements NamedProductsFinderInterface
{
    private QueryBuilderInterface $productsByPartialNameQueryBuilder;

    private FinderInterface $productsFinder;

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
