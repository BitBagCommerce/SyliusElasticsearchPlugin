<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
