<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use UnexpectedValueException;

final class SearchProductsSortDataHandler extends ShopProductsSortDataHandler
{
    public const RELEVANCE = 'relevance';

    public const SCORE_FIELD = '_score';

    public function retrieveData(array $requestData): array
    {
        $orderBy = $requestData[self::ORDER_BY_INDEX] ?? $this->getDefaultOrderBy();

        if (self::RELEVANCE === $orderBy) {
            $sort = $requestData[self::SORT_INDEX] ?? self::SORT_DESC_INDEX;

            if (!in_array($sort, [self::SORT_ASC_INDEX, self::SORT_DESC_INDEX], true)) {
                throw new UnexpectedValueException();
            }

            return ['sort' => [self::SCORE_FIELD => ['order' => strtolower($sort)]]];
        }

        return parent::retrieveData($requestData);
    }

    protected function getDefaultOrderBy(): string
    {
        return self::RELEVANCE;
    }
}
