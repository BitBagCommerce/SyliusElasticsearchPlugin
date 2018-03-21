<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Exception;

use BitBag\SyliusElasticsearchPlugin\Exception\TaxonNotFoundException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class TaxonNotFoundExceptionSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(TaxonNotFoundException::class);
    }

    function it_is_an_not_found_exception(): void
    {
        $this->shouldHaveType(NotFoundHttpException::class);
    }

    function it_has_custom_message(): void
    {
        $this->getMessage()->shouldReturn('Taxon has not been found!');
    }
}
