<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Twig\Extension;

use BitBag\SyliusElasticsearchPlugin\Twig\Extension\UnsetArrayElementsExtension;
use PhpSpec\ObjectBehavior;

final class UnsetArrayElementsExtensionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(UnsetArrayElementsExtension::class);
    }

    function it_is_a_twig_extension(): void
    {
        $this->shouldHaveType(\Twig_Extension::class);
    }

    function it_unset_elments(): void
    {
        $elements = [
            'option_l' => 'L',
            'option_xl' => 'XL',
        ];

        $this->unsetElements($elements, ['option_xl'])->shouldBeEqualTo(['option_l' => 'L']);
    }
}
