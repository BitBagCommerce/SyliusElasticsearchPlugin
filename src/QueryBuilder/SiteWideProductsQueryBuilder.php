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

use Elastica\Query\AbstractQuery;
use Elastica\Query\BoolQuery;
use Webmozart\Assert\Assert;

final class SiteWideProductsQueryBuilder implements QueryBuilderInterface
{
    public function __construct(
        private QueryBuilderInterface $isEnabledQueryBuilder,
        private QueryBuilderInterface $hasChannelQueryBuilder,
        private QueryBuilderInterface $containsNameQueryBuilder
    ) {
    }

    public function buildQuery(array $data): ?AbstractQuery
    {
        $boolQuery = new BoolQuery();

        $isEnabledQuery = $this->isEnabledQueryBuilder->buildQuery([]);
        $hasChannelQuery = $this->hasChannelQueryBuilder->buildQuery([]);

        Assert::notNull($isEnabledQuery);
        Assert::notNull($hasChannelQuery);

        $boolQuery->addMust($isEnabledQuery);
        $boolQuery->addMust($hasChannelQuery);

        $nameQuery = $this->containsNameQueryBuilder->buildQuery($data);
        $this->addMustIfNotNull($nameQuery, $boolQuery);

        return $boolQuery;
    }

    private function addMustIfNotNull(?AbstractQuery $query, BoolQuery $boolQuery): void
    {
        if (null !== $query) {
            $boolQuery->addMust($query);
        }
    }
}
