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
use function sprintf;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class AttributeFacet implements FacetInterface
{
    private ConcatedNameResolverInterface $attributeNameResolver;

    private RepositoryInterface $productAttributeRepository;

    private string $attributeCode;

    private LocaleContextInterface $localeContext;

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
        return sprintf(
            '%s_%s.keyword',
            $this->attributeNameResolver->resolvePropertyName($this->attributeCode),
            $this->localeContext->getLocaleCode()
        );
    }

    private function getProductAttribute(): AttributeInterface
    {
        $attribute = $this->productAttributeRepository->findOneBy(['code' => $this->attributeCode]);
        if (!$attribute instanceof AttributeInterface) {
            throw new RuntimeException(sprintf('Cannot find attribute with code "%s"', $this->attributeCode));
        }

        return $attribute;
    }
}
