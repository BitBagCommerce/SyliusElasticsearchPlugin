<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler;

use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandler;
use BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandlerInterface;
use PhpSpec\ObjectBehavior;

final class PaginationDataHandlerSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(
            9,
        );
    }

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
            PaginationDataHandlerInterface::LIMIT_INDEX => 9,
        ]);
    }
}
