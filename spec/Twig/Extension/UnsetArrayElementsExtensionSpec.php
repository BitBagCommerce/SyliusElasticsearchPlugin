<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
