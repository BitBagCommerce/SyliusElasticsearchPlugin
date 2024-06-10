<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapperInterface;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductVariantRepositoryInterface;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OptionTaxonsBuilder extends AbstractBuilder
{
    public function __construct(
        private RepositoryInterface $productOptionValueRepository,
        private ProductVariantRepositoryInterface $productVariantRepository,
        private ProductTaxonsMapperInterface $productTaxonsMapper,
        private string $taxonsProperty,
        private array $excludedOptions = []
    ) {
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $documentProductOption = $event->getObject();

        if (!$documentProductOption instanceof ProductOptionInterface
            || in_array($documentProductOption->getCode(), $this->excludedOptions, true)
        ) {
            return;
        }

        $document = $event->getDocument();
        $optionValues = $this->productOptionValueRepository->findBy(['option' => $documentProductOption]);
        $taxons = [];

        /** @var ProductOptionValueInterface $optionValue */
        foreach ($optionValues as $optionValue) {
            $option = $optionValue->getOption();
            $productVariants = $this->productVariantRepository->findByOptionValue($optionValue);

            foreach ($productVariants as $productVariant) {
                $product = $productVariant->getProduct();

                if ($documentProductOption === $option && $product->isEnabled()) {
                    $taxons = array_merge($taxons, $this->productTaxonsMapper->mapToUniqueCodes($product));
                }
            }
        }

        $document->set($this->taxonsProperty, array_values(array_unique($taxons)));
    }
}
