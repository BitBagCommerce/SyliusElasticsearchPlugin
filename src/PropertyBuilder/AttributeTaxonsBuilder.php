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

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapperInterface;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\Component\Product\Repository\ProductAttributeValueRepositoryInterface;

final class AttributeTaxonsBuilder extends AbstractBuilder
{
    /**
     * @var ProductAttributeValueRepositoryInterface
     */
    private $productAttributeValueRepository;

    /**
     * @var ProductTaxonsMapperInterface
     */
    private $productTaxonsMapper;

    /**
     * @var string
     */
    private $attributeProperty;

    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @var array
     */
    private $excludedAttributes;

    /**
     * @param ProductAttributeValueRepositoryInterface $productAttributeValueRepository
     * @param ProductTaxonsMapperInterface $productTaxonsMapper
     * @param string $attributeProperty
     * @param string $taxonsProperty
     * @param array $excludedAttributes
     */
    public function __construct(
        ProductAttributeValueRepositoryInterface $productAttributeValueRepository,
        ProductTaxonsMapperInterface $productTaxonsMapper,
        string $attributeProperty,
        string $taxonsProperty,
        array $excludedAttributes = []
    ) {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->productTaxonsMapper = $productTaxonsMapper;
        $this->attributeProperty = $attributeProperty;
        $this->taxonsProperty = $taxonsProperty;
        $this->excludedAttributes = $excludedAttributes;
    }

    /**
     * @param TransformEvent $event
     */
    public function consumeEvent(TransformEvent $event): void
    {
        $documentAttribute = $event->getObject();

        if (!$documentAttribute instanceof AttributeInterface
            || in_array($documentAttribute->getCode(), $this->excludedAttributes)
        ) {
            return;
        }

        $document = $event->getDocument();
        $productAttributes = $this->productAttributeValueRepository->findAll();
        $taxons = [];

        /** @var ProductAttributeValueInterface $attributeValue */
        foreach ($productAttributes as $attributeValue) {
            /** @var ProductInterface $product */
            $product = $attributeValue->getProduct();

            if ($documentAttribute === $attributeValue->getAttribute() && $product->isEnabled()) {
                $taxons = $this->productTaxonsMapper->mapToUniqueCodes($product);
            }
        }

        $document->set($this->taxonsProperty, $taxons);
    }
}
