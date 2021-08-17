<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Attribute\Factory\AttributeFactoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductAttributeContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var RepositoryInterface */
    private $productAttributeRepository;

    /** @var AttributeFactoryInterface */
    private $productAttributeFactory;

    /** @var FactoryInterface */
    private $productAttributeValueFactory;

    /** @var EntityManagerInterface */
    private $objectManager;

    /** @var \Faker\Generator */
    private $faker;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        RepositoryInterface $productAttributeRepository,
        AttributeFactoryInterface $productAttributeFactory,
        FactoryInterface $productAttributeValueFactory,
        EntityManagerInterface $objectManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->productAttributeFactory = $productAttributeFactory;
        $this->productAttributeValueFactory = $productAttributeValueFactory;
        $this->objectManager = $objectManager;

        $this->faker = \Faker\Factory::create();
    }

    /**
     * @Given /^these products have ([^"]+) attribute "([^"]+)"$/
     */
    public function theseProductsHaveTextAttribute(string $productAttributeType, string $productAttributeName): void
    {
        $this->provideProductAttribute($productAttributeType, $productAttributeName);

        $this->objectManager->flush();
    }

    /**
     * @Given /^(\d+) of these products have text attribute "([^"]+)" with "([^"]+)" value$/
     */
    public function ofTheseProductsHaveAttributeWithValue(
        int $quantity,
        string $productAttributeName,
        string $value
    ): void {
        $attribute = $this->provideProductAttribute('text', $productAttributeName);

        $sumQuantity = $this->sharedStorage->has('sum_quantity') ? $this->sharedStorage->get('sum_quantity') : 0;

        $products = $this->sharedStorage->get('products');

        if (count($products) <= $sumQuantity) {
            $sumQuantity = 0;
        }

        $products = array_slice($products, $sumQuantity, $quantity);

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            $attributeValue = $this->createProductAttributeValue($value, $attribute);

            $this->objectManager->persist($attributeValue);

            $product->addAttribute($attributeValue);
        }

        $this->sharedStorage->set('sum_quantity', $sumQuantity + $quantity);

        $this->objectManager->flush();
    }

    private function createProductAttribute(string $type, string $name, ?string $code = null): ProductAttributeInterface
    {
        $productAttribute = $this->productAttributeFactory->createTyped($type);

        $code = $code ?: StringInflector::nameToCode($name);

        $productAttribute->setCode($code);
        $productAttribute->setName($name);

        return $productAttribute;
    }

    private function provideProductAttribute(string $type, string $name, ?string $code = null): ProductAttributeInterface
    {
        $code = $code ?: StringInflector::nameToCode($name);

        /** @var ProductAttributeInterface $productAttribute */
        $productAttribute = $this->productAttributeRepository->findOneBy(['code' => $code]);
        if (null !== $productAttribute) {
            return $productAttribute;
        }

        $productAttribute = $this->createProductAttribute($type, $name, $code);
        $this->saveProductAttribute($productAttribute);

        return $productAttribute;
    }

    private function createProductAttributeValue(
        $value,
        ProductAttributeInterface $attribute,
        string $localeCode = 'en_US'
    ): ProductAttributeValueInterface {
        /** @var ProductAttributeValueInterface $attributeValue */
        $attributeValue = $this->productAttributeValueFactory->createNew();
        $attributeValue->setAttribute($attribute);
        $attributeValue->setValue($value);
        $attributeValue->setLocaleCode($localeCode);

        $this->objectManager->persist($attributeValue);

        return $attributeValue;
    }

    private function saveProductAttribute(ProductAttributeInterface $productAttribute): void
    {
        $this->productAttributeRepository->add($productAttribute);
        $this->sharedStorage->set('product_attribute', $productAttribute);
    }
}
