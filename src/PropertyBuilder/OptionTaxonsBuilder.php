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
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OptionTaxonsBuilder extends AbstractBuilder
{
    private RepositoryInterface $productOptionValueRepository;

    private ProductVariantRepositoryInterface $productVariantRepository;

    private ProductTaxonsMapperInterface $productTaxonsMapper;

    private string $taxonsProperty;

    private array $excludedOptions;

    public function __construct(
        RepositoryInterface $productOptionValueRepository,
        ProductVariantRepositoryInterface $productVariantRepository,
        ProductTaxonsMapperInterface $productTaxonsMapper,
        string $taxonsProperty,
        array $excludedOptions = []
    ) {
        $this->productOptionValueRepository = $productOptionValueRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->productTaxonsMapper = $productTaxonsMapper;
        $this->taxonsProperty = $taxonsProperty;
        $this->excludedOptions = $excludedOptions;
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
        $optionValues = $this->productOptionValueRepository->findAll();
        $taxons = [];

        /** @var ProductOptionValueInterface $optionValue */
        foreach ($optionValues as $optionValue) {
            $option = $optionValue->getOption();
            $productVariant = $this->productVariantRepository->findOneByOptionValue($optionValue);

            if (null === $productVariant) {
                continue;
            }

            /** @var ProductInterface $product */
            $product = $productVariant->getProduct();

            if ($documentProductOption === $option && $product->isEnabled()) {
                $taxons = $this->productTaxonsMapper->mapToUniqueCodes($product);
            }
        }

        $document->set($this->taxonsProperty, array_unique($taxons));
    }
}
