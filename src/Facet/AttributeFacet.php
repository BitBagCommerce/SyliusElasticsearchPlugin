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
use Sylius\Component\Attribute\AttributeType\CheckboxAttributeType;
use Sylius\Component\Attribute\AttributeType\IntegerAttributeType;
use Sylius\Component\Attribute\AttributeType\PercentAttributeType;
use function sprintf;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class AttributeFacet implements FacetInterface
{
    public function __construct(
        private ConcatedNameResolverInterface $attributeNameResolver,
        private AttributeInterface $attribute,
        private LocaleContextInterface $localeContext
    ) {
    }

    public function getAggregation(): AbstractAggregation
    {
        $aggregation = new Terms('');
        $aggregation->setField($this->getFieldName());

        return $aggregation;
    }

    public function getQuery(array $selectedBuckets): AbstractQuery
    {
        match ($this->getProductAttribute()->getType()) {
            CheckboxAttributeType::TYPE => $selectedBuckets = array_map('boolval', $selectedBuckets),
            PercentAttributeType::TYPE => $selectedBuckets = array_map('strval', $selectedBuckets),
            IntegerAttributeType::TYPE => $selectedBuckets = array_map('intval', $selectedBuckets),
            default => $selectedBuckets,
        };

        return new TermsQuery($this->getFieldName(), $selectedBuckets);
    }

    public function getBucketLabel(array $bucket): string
    {
        $label = ucwords(str_replace('_', ' ', (string) $bucket['key']));

        return sprintf('%s (%s)', $label, $bucket['doc_count']);
    }

    public function getLabel(): string
    {
        return (string) $this->getProductAttribute()->getName();
    }

    private function getFieldName(): string
    {
        $isKeywordAvailable = match ($this->getProductAttribute()->getType()) {
            CheckboxAttributeType::TYPE => false,
            PercentAttributeType::TYPE => false,
            IntegerAttributeType::TYPE => false,
            default => true,
        };

        return sprintf(
            '%s_%s' . ($isKeywordAvailable ? '.keyword' : ''),
            $this->attributeNameResolver->resolvePropertyName((string) $this->getProductAttribute()->getCode()),
            $this->localeContext->getLocaleCode()
        );
    }

    private function getProductAttribute(): AttributeInterface
    {
        return $this->attribute;
    }
}
