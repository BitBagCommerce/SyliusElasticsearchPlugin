<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use PhpSpec\ObjectBehavior;

final class PaginationDataHandlerSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(PaginationDataHandler::class);
    }

    function it_implements_pagination_data_handler_interface(): void
    {
        $this->shouldHaveType(PaginationDataHandlerInterface::class);
    }

    function it_retrieves_data(): void
    {
        $this->retrieveData([])->shouldBeEqualTo([
            PaginationDataHandlerInterface::PAGE_INDEX => 1,
            PaginationDataHandlerInterface::LIMIT_INDEX => PaginationDataHandlerInterface::DEFAULT_LIMIT,
        ]);
    }
}
