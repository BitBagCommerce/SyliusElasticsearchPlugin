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
use Doctrine\Common\Collections\ArrayCollection;
use Liip\ImagineBundle\Service\FilterService;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class ImageTransformerSpec extends ObjectBehavior
{
    function let(FilterService $filterService): void
    {
        $this->beConstructedWith($filterService);
    }

    function it_is_a_transformer(): void
    {
        $this->shouldImplement(TransformerInterface::class);
    }

    function it_transforms_product_images_into_product_thumbnail(
        ProductInterface $product,
        ImageInterface $productImage,
        FilterService $filterService
    ): void {
        $product->getImagesByType('thumbnail')->willReturn(new ArrayCollection([$productImage->getWrappedObject()]));
        $productImage->getPath()->willReturn('/path-to-image');

        $filterService
            ->getUrlOfFilteredImage('/path-to-image', 'sylius_shop_product_thumbnail')
            ->shouldBeCalled()
        ;

        $this->transform($product);
    }
}
