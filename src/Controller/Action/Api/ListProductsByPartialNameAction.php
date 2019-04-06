<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Controller\Action\Api;

use BitBag\SyliusElasticsearchPlugin\Controller\Response\DTO\Item;
use BitBag\SyliusElasticsearchPlugin\Controller\Response\ItemsResponse;
use BitBag\SyliusElasticsearchPlugin\Finder\NamedProductsFinderInterface;
use Liip\ImagineBundle\Service\FilterService;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListProductsByPartialNameAction
{
    private const SYLIUS_THUMBNAIL_TYPE = 'thumbnail';
    private const SYLIUS_THUMBNAIL_FILTER = 'sylius_shop_product_thumbnail';

    /** @var NamedProductsFinderInterface */
    private $namedProductsFinder;

    /** @var ProductVariantResolverInterface */
    private $productVariantResolver;

    /** @var ChannelContextInterface */
    private $channelContext;

    /** @var MoneyFormatterInterface */
    private $moneyFormatter;

    /** @var FilterService */
    private $imagineFilter;

    public function __construct(
        NamedProductsFinderInterface $namedProductsFinder,
        ProductVariantResolverInterface $productVariantResolver,
        ChannelContextInterface $channelContext,
        MoneyFormatterInterface $moneyFormatter,
        FilterService $imagineFilter
    ) {
        $this->namedProductsFinder = $namedProductsFinder;
        $this->productVariantResolver = $productVariantResolver;
        $this->channelContext = $channelContext;
        $this->moneyFormatter = $moneyFormatter;
        $this->imagineFilter = $imagineFilter;
    }

    public function __invoke(Request $request): Response
    {
        $itemsResponse = ItemsResponse::createEmpty();

        if (null === $request->query->get('query')) {
            return JsonResponse::create($itemsResponse->toArray());
        }

        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();

        $products = $this->namedProductsFinder->findByNamePart($request->query->get('query'));

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            if (null === $productMainTaxon = $product->getMainTaxon()) {
                continue;
            }

            $itemsResponse->addItem(new Item(
                $productMainTaxon->getName(),
                $product->getName(),
                $product->getShortDescription(),
                $product->getSlug(),
                $this->resolveChannelProductPrice($channel, $product),
                $this->resolveProductImage($product)
            ));
        }

        return JsonResponse::create($itemsResponse->toArray());
    }

    private function resolveChannelProductPrice(ChannelInterface $channel, ProductInterface $product): ?string
    {
        if (null === $channelBaseCurrency = $channel->getBaseCurrency()) {
            throw new \RuntimeException('No channel currency configured');
        }

        /** @var ProductVariantInterface $productVariant */
        $productVariant = $this->productVariantResolver->getVariant($product);

        $productVariantPricing = $productVariant->getChannelPricingForChannel($channel);

        if (null === $productVariantPricing) {
            return null;
        }

        return $this->moneyFormatter->format($productVariantPricing->getPrice(), $channelBaseCurrency->getCode());
    }

    private function resolveProductImage(ProductInterface $product): ?string
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
