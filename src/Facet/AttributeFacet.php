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
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class AttributeFacet implements FacetInterface
{
    /** @var ConcatedNameResolverInterface */
    private $attributeNameResolver;

    /** @var RepositoryInterface */
    private $productAttributeRepository;

    /** @var string */
    private $attributeCode;

    /** @var LocaleContextInterface */
    private $localeContext;

    public function __construct(
        ConcatedNameResolverInterface $attributeNameResolver,
        RepositoryInterface $productAttributeRepository,
        string $attributeCode,
        LocaleContextInterface $localeContext
    ) {
        $this->attributeNameResolver = $attributeNameResolver;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->attributeCode = $attributeCode;
        $this->localeContext = $localeContext;
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
        return $this->getProductAttribute()->getName();
    }

    private function getFieldName(): string
    {
        return \sprintf(
            '%s_%s.keyword',
            $this->attributeNameResolver->resolvePropertyName($this->attributeCode),
            $this->localeContext->getLocaleCode()
        );
    }

    private function getProductAttribute(): AttributeInterface
    {
        $attribute = $this->productAttributeRepository->findOneBy(['code' => $this->attributeCode]);
        if (!$attribute instanceof AttributeInterface) {
            throw new \RuntimeException(sprintf('Cannot find attribute with code "%s"', $this->attributeCode));
        }

        return $attribute;
    }
}
