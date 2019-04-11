<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Transformer\Product;

use BitBag\SyliusElasticsearchPlugin\Transformer\Product\TransformerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Routing\RouterInterface;

final class SlugTransformerSpec extends ObjectBehavior
{
    function let(RouterInterface $router): void
    {
        $this->beConstructedWith($router);
    }

    function it_is_a_transformer(): void
    {
        $this->shouldImplement(TransformerInterface::class);
    }

    function it_transforms_product_slug_into_route(RouterInterface $router, ProductInterface $product): void
    {
        $product->getSlug()->willReturn('/super-quirky-shirt');

        $router->generate('sylius_shop_product_show', ['slug' => '/super-quirky-shirt'])->shouldBeCalled();

        $this->transform($product);
    }
}
