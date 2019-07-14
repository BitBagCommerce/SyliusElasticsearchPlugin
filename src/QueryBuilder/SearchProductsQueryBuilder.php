<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use Elastica\Query\AbstractQuery;
use Elastica\Query\MultiMatch;

final class SearchProductsQueryBuilder implements QueryBuilderInterface
{
    public const QUERY_KEY = 'query';

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
        // TODO set search fields here (pay attention to locale-contex field, like name): $query->setFields([]);
        return $multiMatch;
    }
}
