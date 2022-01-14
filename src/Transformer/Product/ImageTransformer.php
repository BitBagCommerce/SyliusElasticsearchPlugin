<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Product;

use Liip\ImagineBundle\Service\FilterService;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class ImageTransformer implements TransformerInterface
{
    private const SYLIUS_THUMBNAIL_TYPE = 'thumbnail';

    private const SYLIUS_THUMBNAIL_FILTER = 'sylius_shop_product_thumbnail';

    /** @var FilterService */
    private $imagineFilter;

    public function __construct(FilterService $imagineFilter)
    {
        $this->imagineFilter = $imagineFilter;
    }

    public function transform(ProductInterface $product): ?string
    {
        $productThumbnails = $product->getImagesByType(self::SYLIUS_THUMBNAIL_TYPE);

        if ($productThumbnails->isEmpty()) {
            return null;
        }

        /** @var ImageInterface $productImage */
        $productImage = $productThumbnails->first();

        return $this->imagineFilter->getUrlOfFilteredImage($productImage->getPath(), self::SYLIUS_THUMBNAIL_FILTER);
    }
}
