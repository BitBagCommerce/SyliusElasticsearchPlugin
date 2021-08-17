<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
