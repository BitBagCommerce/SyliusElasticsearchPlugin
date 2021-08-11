<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
