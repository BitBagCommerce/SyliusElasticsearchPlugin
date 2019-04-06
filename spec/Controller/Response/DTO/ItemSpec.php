<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO;

use PhpSpec\ObjectBehavior;

final class ItemSpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(
            'Super cars',
            'McLaren F1',
            'Very quirky super-car',
            '/mc-laren/f1',
            '$22,000,000.00',
            ''
        );
    }

    function it_returns_an_array(): void
    {
        $this->toArray()->shouldReturn([
            'taxon_name' => 'Super cars',
            'name' => 'McLaren F1',
            'description' => 'Very quirky super-car',
            'slug' => '/mc-laren/f1',
            'price' => '$22,000,000.00',
            'image' => '',
        ]);
    }

    function it_returns_values(): void
    {
        $this->taxonName()->shouldReturn('Super cars');
        $this->name()->shouldReturn('McLaren F1');
        $this->description()->shouldReturn('Very quirky super-car');
        $this->slug()->shouldReturn('/mc-laren/f1');
        $this->price()->shouldReturn('$22,000,000.00');
        $this->image()->shouldReturn('');
    }
}
