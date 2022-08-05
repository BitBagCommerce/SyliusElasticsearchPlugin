<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\Response;

use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Item;
use PhpSpec\ObjectBehavior;

final class ItemsResponseSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedThrough('createEmpty');
    }

    function it_can_add_items(): void
    {
        $this->addItem(new Item(
            'Super cars',
            'McLaren F1',
            'Very quirky super-car',
            '/mc-laren/f1',
            '$22,000,000.00',
            ''
        ));

        $this->all()->shouldHaveCount(1);
    }

    function it_returns_an_array(): void
    {
        $this->toArray()->shouldBeArray();
    }
}
