<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

interface SortDataHandlerInterface extends DataHandlerInterface
{
    public const ORDER_BY_INDEX = 'order_by';

    public const SORT_INDEX = 'sort';

    public const SORT_ASC_INDEX = 'asc';

    public const SORT_DESC_INDEX = 'desc';
}
