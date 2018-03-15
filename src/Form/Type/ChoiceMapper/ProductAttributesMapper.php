<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper;

use BitBag\SyliusElasticsearchPlugin\PropertyValueResolver\AttributeValueResolverInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\Component\Product\Repository\ProductAttributeValueRepositoryInterface;

final class ProductAttributesMapper implements ProductAttributesMapperInterface
{
    /**
     * @var ProductAttributeValueRepositoryInterface
     */
    private $productAttributeValueRepository;

    /**
     * @var AttributeValueResolverInterface
     */
    private $attributeValueResolver;

    /**
     * @param ProductAttributeValueRepositoryInterface $productAttributeValueRepository
     * @param AttributeValueResolverInterface $attributeValueResolver
     */
    public function __construct(
        ProductAttributeValueRepositoryInterface $productAttributeValueRepository,
        AttributeValueResolverInterface $attributeValueResolver
    ) {
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->attributeValueResolver = $attributeValueResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function mapToChoices(ProductAttributeInterface $productAttribute): array
    {
        $attributeValues = $this->productAttributeValueRepository->findBy(['attribute' => $productAttribute]);
        $choices = [];
        array_walk($attributeValues, function (ProductAttributeValueInterface $productAttributeValue) use (&$choices) {
            $value = $this->attributeValueResolver->resolve($productAttributeValue);
            $choices[$productAttributeValue->getValue()] = $value;
        });

        return $choices;
    }
}
