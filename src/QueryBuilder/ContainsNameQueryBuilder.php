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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\SearchPropertyNameResolverRegistryInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\MultiMatch;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class ContainsNameQueryBuilder implements QueryBuilderInterface
{
    public function __construct(
        private LocaleContextInterface $localeContext,
        private SearchPropertyNameResolverRegistryInterface $searchProperyNameResolverRegistry,
        private string $fuzziness = 'AUTO'
    ) {
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $localeCode = $this->localeContext->getLocaleCode();
        $query = $data['name'] ?? $data['query'] ?? null;

        if (null === $query || '' === $query) {
            return null;
        }

        $fields = [];
        foreach ($this->searchProperyNameResolverRegistry->getPropertyNameResolvers() as $propertyNameResolver) {
            $fields[] = $propertyNameResolver->resolvePropertyName($localeCode);
        }

        $multiMatch = new MultiMatch();
        $multiMatch->setQuery($query);
        $multiMatch->setFuzziness($this->fuzziness);
        $multiMatch->setFields($fields);

        return $multiMatch;
    }
}
