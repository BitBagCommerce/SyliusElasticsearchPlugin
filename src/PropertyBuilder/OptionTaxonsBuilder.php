<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    /** @var RepositoryInterface */
    private $productOptionValueRepository;

    /** @var ProductVariantRepositoryInterface */
    private $productVariantRepository;

    /** @var ProductTaxonsMapperInterface */
    private $productTaxonsMapper;

    /** @var string */
    private $taxonsProperty;

    /** @var array */
    private $excludedOptions;

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
