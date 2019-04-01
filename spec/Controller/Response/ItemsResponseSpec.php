<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
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
