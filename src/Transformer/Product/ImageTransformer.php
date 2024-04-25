<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Product;

use Liip\ImagineBundle\Service\FilterService;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class ImageTransformer implements TransformerInterface
{
    private const SYLIUS_THUMBNAIL_TYPE = 'main';

    private const SYLIUS_THUMBNAIL_FILTER = 'sylius_shop_product_thumbnail';

    private FilterService $imagineFilter;

    private string $imagesPath;

    public function __construct(FilterService $imagineFilter, string $imagesPath = '/media/image/')
    {
        $this->imagineFilter = $imagineFilter;
        $this->imagesPath = $imagesPath;
    }

    public function transform(ProductInterface $product): ?string
    {
        $productThumbnails = $product->getImagesByType(self::SYLIUS_THUMBNAIL_TYPE);

        if ($productThumbnails->isEmpty()) {
            return null;
        }

        /** @var ImageInterface $productImage */
        $productImage = $productThumbnails->first();

        if ($this->canImageBeFiltered($productImage->getPath())) {
            return $this->imagineFilter->getUrlOfFilteredImage($productImage->getPath(), self::SYLIUS_THUMBNAIL_FILTER);
        }

        return $this->imagesPath . $productImage->getPath();
    }

    private function canImageBeFiltered(string $imagePath): bool
    {
        return !str_ends_with($imagePath, 'svg');
    }
}
