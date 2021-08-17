<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
