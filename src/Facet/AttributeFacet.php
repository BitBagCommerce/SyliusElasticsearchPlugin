<?php
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
    /**
     * @var ConcatedNameResolverInterface
     */
    private $attributeNameResolver;
    /**
     * @var RepositoryInterface
     */
    private $productAttributeRepository;
    /**
     * @var LocaleContextInterface
     */
    private $localeContext;
    /**
     * @var string
     */
    private $attributeCode;

    public function __construct(
        ConcatedNameResolverInterface $attributeNameResolver,
        RepositoryInterface $productAttributeRepository,
        LocaleContextInterface $localeContext,
        string $attributeCode
    ) {
        $this->attributeNameResolver = $attributeNameResolver;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->localeContext = $localeContext;
        $this->attributeCode = $attributeCode;
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
        $attribute = $this->getProductAttribute();
        $label = $value = $bucket['key'];
        if ($attribute->getType() === 'select') {
            $configuration = $attribute->getConfiguration();
            if (isset($configuration['choices'][$value][$this->localeContext->getLocaleCode()])) {
                $label = $configuration['choices'][$value][$this->localeContext->getLocaleCode()];
            }
        } else {
            $label = ucwords(str_replace('_', ' ', $label));
        }
        return sprintf('%s (%s)', $label, $bucket['doc_count']);
    }

    public function getLabel(): string
    {
        return $this->getProductAttribute()->getName();
    }

    /**
     * @return string
     */
    private function getFieldName(): string
    {
        return $this->attributeNameResolver->resolvePropertyName($this->attributeCode) . '.keyword';
    }

    /**
     * @return AttributeInterface
     */
    private function getProductAttribute(): AttributeInterface
    {
        $attribute = $this->productAttributeRepository->findOneBy(['code' => $this->attributeCode]);
        if (!$attribute instanceof AttributeInterface) {
            throw new \RuntimeException(sprintf('Cannot find attribute with code "%s"', $this->attributeCode));
        }
        return $attribute;
    }
}
