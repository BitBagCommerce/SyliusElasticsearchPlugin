<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\MatchQuery;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ContainsNameQueryBuilder implements QueryBuilderInterface
{
    /** @var LocaleContextInterface */
    private $localeContext;

    /** @var ConcatedNameResolverInterface */
    private $productNameNameResolver;

    /** @var string */
    private $namePropertyPrefix;

    public function __construct(
        LocaleContextInterface $localeContext,
        ConcatedNameResolverInterface $productNameNameResolver,
        string $namePropertyPrefix
    ) {
        $this->localeContext = $localeContext;
        $this->productNameNameResolver = $productNameNameResolver;
        $this->namePropertyPrefix = $namePropertyPrefix;
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $localeCode = $this->localeContext->getLocaleCode();
        $propertyName = $this->productNameNameResolver->resolvePropertyName($localeCode);

        if (!$name = $data[$this->namePropertyPrefix]) {
            return null;
        }

        $nameQuery = new MatchQuery();

        $nameQuery->setFieldQuery($propertyName, $name);
        $nameQuery->setFieldFuzziness($propertyName, 2);
        $nameQuery->setFieldMinimumShouldMatch($propertyName, 2);

        return $nameQuery;
    }
}
