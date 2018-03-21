<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Formatter;

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatter;
use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use PhpSpec\ObjectBehavior;

final class StringFormatterSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(StringFormatter::class);
    }

    function it_implements_string_formatter_interface(): void
    {
        $this->shouldHaveType(StringFormatterInterface::class);
    }

    function it_formats_to_lowercase_without_spaces(): void
    {
        $this->formatToLowercaseWithoutSpaces('StrIng in-De-x')->shouldBeEqualTo('string_in_de_x');
    }
}
