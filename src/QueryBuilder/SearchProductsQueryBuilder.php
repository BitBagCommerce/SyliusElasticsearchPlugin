<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\SearchPropertyNameResolverRegistryInterface;
use Elastica\Query\AbstractQuery;
use Elastica\Query\MultiMatch;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class SearchProductsQueryBuilder implements QueryBuilderInterface
{
    public const QUERY_KEY = 'query';

    /**
     * @var SearchPropertyNameResolverRegistryInterface
     */
    private $searchProperyNameResolverRegistry;
    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    public function __construct(
        SearchPropertyNameResolverRegistryInterface $searchProperyNameResolverRegistry,
        LocaleContextInterface $localeContext
    ) {
        $this->searchProperyNameResolverRegistry = $searchProperyNameResolverRegistry;
        $this->localeContext = $localeContext;
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
        if (!is_string($data[self::QUERY_KEY])) {
            throw new \RuntimeException(
                sprintf(
                    'Could not build search products query because the provided "query" is expected to be a string ' .
                    'but "%s" is given.',
                    is_object($data[self::QUERY_KEY]) ? get_class($data[self::QUERY_KEY]) : gettype($data[self::QUERY_KEY])
                )
            );
        }

        $multiMatch = new MultiMatch();
        $multiMatch->setQuery($data['query']);
        $multiMatch->setFuzziness('AUTO');
        $fields = [];
        foreach ($this->searchProperyNameResolverRegistry->getPropertyNameResolvers() as $propertyNameResolver) {
            $fields[] = $propertyNameResolver->resolvePropertyName($this->localeContext->getLocaleCode());
        }
        $multiMatch->setFields($fields);
        return $multiMatch;
    }
}
