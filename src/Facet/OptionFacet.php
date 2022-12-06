<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Terms;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Terms as TermsQuery;
use RuntimeException;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OptionFacet implements FacetInterface
{
    private ConcatedNameResolverInterface $optionNameResolver;

    private RepositoryInterface $productOptionRepository;

    private string $productOptionCode;

    public function __construct(
        ConcatedNameResolverInterface $optionNameResolver,
        RepositoryInterface $productOptionRepository,
        string $optionCode
    ) {
        $this->optionNameResolver = $optionNameResolver;
        $this->productOptionRepository = $productOptionRepository;
        $this->productOptionCode = $optionCode;
    }

    public function getAggregation(): AbstractAggregation
    {
        $aggregation = new Terms('');
        $aggregation->setField($this->getFieldName());

        return $aggregation;
    }

    public function getQuery(array $selectedBuckets): AbstractQuery
    {
        return new TermsQuery($this->getFieldName(), $selectedBuckets);
    }

    public function getBucketLabel(array $bucket): string
    {
        $label = ucwords(str_replace('_', ' ', $bucket['key']));

        return sprintf('%s (%s)', $label, $bucket['doc_count']);
    }

    public function getLabel(): string
    {
        $productOption = $this->productOptionRepository->findOneBy(['code' => $this->productOptionCode]);
        if (!$productOption instanceof ProductOptionInterface) {
            throw new RuntimeException(sprintf('Cannot find product option with code "%s"', $this->productOptionCode));
        }

        return $productOption->getName();
    }

    private function getFieldName(): string
    {
        return $this->optionNameResolver->resolvePropertyName($this->productOptionCode) . '.keyword';
    }
}
