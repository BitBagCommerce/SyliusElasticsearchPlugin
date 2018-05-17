<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\EntityRepository\ProductVariantRepositoryInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapperInterface;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OptionTaxonsBuilder extends AbstractBuilder
{
    /**
     * @var RepositoryInterface
     */
    private $productOptionValueRepository;

    /**
     * @var ProductVariantRepositoryInterface
     */
    private $productVariantRepository;

    /**
     * @var ProductTaxonsMapperInterface
     */
    private $productTaxonsMapper;

    /**
     * @var string
     */
    private $optionProperty;

    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @var array
     */
    private $excludedOptions;

    /**
     * @param RepositoryInterface $productOptionValueRepository
     * @param ProductVariantRepositoryInterface $productVariantRepository
     * @param ProductTaxonsMapperInterface $productTaxonsMapper
     * @param string $optionProperty
     * @param string $taxonsProperty
     * @param array $excludedOptions
     */
    public function __construct(
        RepositoryInterface $productOptionValueRepository,
        ProductVariantRepositoryInterface $productVariantRepository,
        ProductTaxonsMapperInterface $productTaxonsMapper,
        string $optionProperty,
        string $taxonsProperty,
        array $excludedOptions = []
    ) {
        $this->productOptionValueRepository = $productOptionValueRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->productTaxonsMapper = $productTaxonsMapper;
        $this->optionProperty = $optionProperty;
        $this->taxonsProperty = $taxonsProperty;
        $this->excludedOptions = $excludedOptions;
    }

    /**
     * @param TransformEvent $event
     */
    public function consumeEvent(TransformEvent $event): void
    {
        $documentProductOption = $event->getObject();

        if (!$documentProductOption instanceof ProductOptionInterface
            || in_array($documentProductOption->getCode(), $this->excludedOptions)
        ) {
            return;
        }

        $document = $event->getDocument();
        $optionValues = $this->productOptionValueRepository->findAll();
        $taxons = [];

        /** @var ProductOptionValueInterface $optionValue */
        foreach ($optionValues as $optionValue) {
            $option = $optionValue->getOption();
            /** @var ProductInterface $product */
            $productVariant = $this->productVariantRepository->findOneByOptionValue($optionValue);

            if (null === $productVariant) {
                continue;
            }

            $product = $productVariant->getProduct();

            if ($documentProductOption === $option && $product->isEnabled()) {
                $taxons = $this->productTaxonsMapper->mapToUniqueCodes($product);
            }
        }

        $document->set($this->taxonsProperty, $taxons);
    }
}
