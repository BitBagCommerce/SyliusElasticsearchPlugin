<?php
/*

This file was created by developers working at BitBag

Do you need more information about us and what we do? Visit our   website!

We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\QueryBuilder;

use Elastica\Query\AbstractQuery;
use Elastica\Query\Terms;

final class HasAuthorizedGroup implements QueryBuilderInterface
{

    public function buildQuery(array $data): ?AbstractQuery
    {
        $authorizedGroupQuery = new Terms();
        $authorizedGroupQuery->setTerms($authorizedGroupQuery);

        return $authorizedGroupQuery;
    }
}
