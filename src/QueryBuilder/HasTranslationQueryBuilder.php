<?php

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Exists;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class HasTranslationQueryBuilder implements QueryBuilderInterface
{
    /** @var LocaleContextInterface */
    private $localeContext;

    /** @var ConcatedNameResolverInterface */
    private $productNameNameResolver;

    public function __construct(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver
    ) {
        $this->localeContext = $localeContext;
        $this->productNameNameResolver = $productNameNameResolver;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $localeCode = $this->localeContext->getLocaleCode();
        $propertyName = $this->productNameNameResolver->resolvePropertyName($localeCode);

        return new Exists($propertyName);
    }
}