<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\MatchQuery;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ContainsNameQueryBuilder implements QueryBuilderInterface
{
    public function __construct(
        private LocaleContextInterface $localeContext,
        private ConcatedNameResolverInterface $productNameNameResolver,
        private string $namePropertyPrefix
    ) {
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
