<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Terms;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Terms as TermsQuery;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OptionFacet implements FacetInterface
{
    /** @var ConcatedNameResolverInterface */
    private $optionNameResolver;

    /** @var RepositoryInterface */
    private $productOptionRepository;

    /** @var string */
    private $productOptionCode;

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
            throw new \RuntimeException(sprintf('Cannot find product option with code "%s"', $this->productOptionCode));
        }

        return $productOption->getName();
    }

    private function getFieldName(): string
    {
        return $this->optionNameResolver->resolvePropertyName($this->productOptionCode) . '.keyword';
    }
}
