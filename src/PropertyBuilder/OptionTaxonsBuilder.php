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

use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;

final class OptionTaxonsBuilder extends AbstractBuilder
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

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
     * @param ProductRepositoryInterface $productRepository
     * @param string $optionProperty
     * @param string $taxonsProperty
     * @param array $excludedOptions
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        string $optionProperty,
        string $taxonsProperty,
        array $excludedOptions = []
    ) {
        $this->productRepository = $productRepository;
        $this->optionProperty = $optionProperty;
        $this->taxonsProperty = $taxonsProperty;
        $this->excludedOptions = $excludedOptions;
    }

    /**
     * @param TransformEvent $event
     */
    public function buildProperty(TransformEvent $event): void
    {
        $documentProductOption = $event->getObject();

        if (
            !$documentProductOption instanceof ProductOptionInterface ||
            in_array($documentProductOption->getCode(), $this->excludedOptions)
        ) {
            return;
        }

        $document = $event->getDocument();
        $products = $this->productRepository->findAll();
        $taxons = [];

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            foreach ($product->getVariants() as $productVariant) {
                foreach ($productVariant->getOptionValues() as $productOptionValue) {
                    if ($documentProductOption === $productOptionValue->getOption()) {
                        foreach ($product->getTaxons() as $taxon) {
                            $code = $taxon->getCode();
                            if (!in_array($code, $taxons)) {
                                $taxons[] = $code;
                            }
                        }
                    }
                }
            }
        }

        $document->set($this->taxonsProperty, $taxons);
    }
}
