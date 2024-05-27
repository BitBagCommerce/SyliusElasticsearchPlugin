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
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class SiteWideProductsQueryBuilder implements QueryBuilderInterface
{
    public const QUERY_KEY = 'query';

    public function __construct(
        private SearchPropertyNameResolverRegistryInterface $searchProperyNameResolverRegistry,
        private LocaleContextInterface $localeContext,
        private QueryBuilderInterface $isEnabledQueryBuilder,
        private QueryBuilderInterface $hasChannelQueryBuilder,
        private string $fuzziness
    ) {
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        if (!array_key_exists(self::QUERY_KEY, $data)) {
            throw new \RuntimeException(
                sprintf(
                    'Could not build search products query because there\'s no "query" key in provided data. ' .
                    'Got the following keys: %s',
                    implode(', ', array_keys($data))
                )
            );
        }
        $query = $data[self::QUERY_KEY];
        if (!is_string($query)) {
            throw new \RuntimeException(
                sprintf(
                    'Could not build search products query because the provided "query" is expected to be a string ' .
                    'but "%s" is given.',
                    is_object($query) ? get_class($query) : gettype($query)
                )
            );
        }

        $multiMatch = new MultiMatch();
        $multiMatch->setQuery($query);
        $multiMatch->setFuzziness($this->fuzziness);
        $fields = [];
        foreach ($this->searchProperyNameResolverRegistry->getPropertyNameResolvers() as $propertyNameResolver) {
            $fields[] = $propertyNameResolver->resolvePropertyName($this->localeContext->getLocaleCode());
        }
        $multiMatch->setFields($fields);
        $bool = new BoolQuery();
        $bool->addMust($multiMatch);
        $bool->addFilter($this->isEnabledQueryBuilder->buildQuery([]));
        $bool->addFilter($this->hasChannelQueryBuilder->buildQuery([]));

        return $bool;
    }
}
