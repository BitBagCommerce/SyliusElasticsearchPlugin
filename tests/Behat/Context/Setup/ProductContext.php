<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductFactoryInterface
     */
    private $productFactory;

    /**
     * @var FactoryInterface
     */
    private $channelPricingFactory;

    /**
     * @var FactoryInterface
     */
    private $productOptionFactory;

    /**
     * @var FactoryInterface
     */
    private $productOptionValueFactory;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ProductVariantResolverInterface
     */
    private $defaultVariantResolver;

    /**
     * @var SlugGeneratorInterface
     */
    private $slugGenerator;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @param SharedStorageInterface $sharedStorage
     * @param ProductRepositoryInterface $productRepository
     * @param ProductFactoryInterface $productFactory
     * @param FactoryInterface $channelPricingFactory
     * @param FactoryInterface $productOptionFactory
     * @param FactoryInterface $productOptionValueFactory
     * @param ObjectManager $objectManager
     * @param ProductVariantResolverInterface $defaultVariantResolver
     * @param SlugGeneratorInterface $slugGenerator
     */
    public function __construct(
        SharedStorageInterface $sharedStorage,
        ProductRepositoryInterface $productRepository,
        ProductFactoryInterface $productFactory,
        FactoryInterface $channelPricingFactory,
        FactoryInterface $productOptionFactory,
        FactoryInterface $productOptionValueFactory,
        ObjectManager $objectManager,
        ProductVariantResolverInterface $defaultVariantResolver,
        SlugGeneratorInterface $slugGenerator
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
        $this->channelPricingFactory = $channelPricingFactory;
        $this->productOptionFactory = $productOptionFactory;
        $this->productOptionValueFactory = $productOptionValueFactory;
        $this->objectManager = $objectManager;
        $this->defaultVariantResolver = $defaultVariantResolver;
        $this->slugGenerator = $slugGenerator;

        $this->faker = \Faker\Factory::create();
    }

    /**
     * @Given there are :quantity t-shirts in the store
     */
    public function thereAreTShirtsInTheStore(int $quantity): void
    {
        $products = [];

        for ($i = 0; $i < $quantity; ++$i) {
            $products[] = $product = $this->createProduct('T-shirt ' . uniqid());

            $this->saveProduct($product);
        }

        $this->sharedStorage->set('products', $products);
    }

    /**
     * @Given /^(\d+) of these products are priced between ("[^"]+") and ("[^"]+")$/
     */
    public function ofTheseProductsArePricedBetweenAnd(int $quantity, int $min, int $max): void
    {
        $channel = $this->sharedStorage->get('channel');

        $sumQuantity = $this->sharedStorage->has('sum_quantity') ? $this->sharedStorage->get('sum_quantity') : 0;

        $products = $this->sharedStorage->get('products');

        if (count($products) <= $sumQuantity) {
            $sumQuantity = 0;
        }

        $products = array_slice($products, $sumQuantity, $quantity);

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            /** @var ProductVariantInterface $productVariant */
            $productVariant = $product->getVariants()->first();

            $channelPricing = $productVariant->getChannelPricingForChannel($channel);

            $channelPricing->setPrice($this->faker->numberBetween($min, $max));
        }

        $this->sharedStorage->set('sum_quantity', $sumQuantity + $quantity);

        $this->objectManager->flush();
    }

    /**
     * @Given these products have :optionName option with values :values
     */
    public function theseHaveOption(string $optionName, string $values): void
    {
        /** @var ProductOptionInterface $option */
        $option = $this->productOptionFactory->createNew();

        $option->setName($optionName);
        $option->setCode(StringInflector::nameToUppercaseCode($optionName));

        $this->sharedStorage->set(sprintf('%s_option', $optionName), $option);

        foreach (explode(',', $values) as $value) {
            $optionValue = $this->addProductOption($option, $value, StringInflector::nameToUppercaseCode($value));
            $this->sharedStorage->set(sprintf('%s_option_%s_value', $value, strtolower($optionName)), $optionValue);
        }

        /** @var ProductInterface $product */
        foreach ($this->sharedStorage->get('products') as $product) {
            $product->addOption($option);
            $product->setVariantSelectionMethod(ProductInterface::VARIANT_SELECTION_MATCH);
        }

        $this->objectManager->persist($option);
        $this->objectManager->flush();
    }

    /**
     * @Given :quantity of these products have :optionName option with :value value
     */
    public function ofTheseProductsHaveOptionWithValue(int $quantity, string $optionName, string $value): void
    {
        $sumQuantity = $this->sharedStorage->has('sum_quantity') ? $this->sharedStorage->get('sum_quantity') : 0;

        $products = $this->sharedStorage->get('products');

        if (count($products) <= $sumQuantity) {
            $sumQuantity = 0;
        }

        $products = array_slice($products, $sumQuantity, $quantity);

        $optionValue = $this->sharedStorage->get(sprintf('%s_option_%s_value', $value, strtolower($optionName)));

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            /** @var ProductVariantInterface $productVariant */
            $productVariant = $product->getVariants()->first();

            $productVariant->addOptionValue($optionValue);
        }

        $this->sharedStorage->set('sum_quantity', $sumQuantity + $quantity);

        $this->objectManager->flush();
    }

    /**
     * @param string $productName
     * @param int $price
     * @param ChannelInterface|null $channel
     *
     * @return ProductInterface
     */
    private function createProduct(string $productName, int $price = 100, ChannelInterface $channel = null): ProductInterface
    {
        if (null === $channel && $this->sharedStorage->has('channel')) {
            $channel = $this->sharedStorage->get('channel');
        }

        /** @var ProductInterface $product */
        $product = $this->productFactory->createWithVariant();

        $product->setCode(StringInflector::nameToUppercaseCode($productName));
        $product->setName($productName);
        $product->setSlug($this->slugGenerator->generate($productName));

        if (null !== $channel) {
            $product->addChannel($channel);

            foreach ($channel->getLocales() as $locale) {
                $product->setFallbackLocale($locale->getCode());
                $product->setCurrentLocale($locale->getCode());

                $product->setName($productName);
                $product->setSlug($this->slugGenerator->generate($productName));
            }
        }

        /** @var ProductVariantInterface $productVariant */
        $productVariant = $this->defaultVariantResolver->getVariant($product);

        if (null !== $channel) {
            $productVariant->addChannelPricing($this->createChannelPricingForChannel($price, $channel));
        }

        $productVariant->setCode($product->getCode());
        $productVariant->setName($product->getName());

        return $product;
    }

    /**
     * @param ProductOptionInterface $option
     * @param string $value
     * @param string $code
     *
     * @return ProductOptionValueInterface
     */
    private function addProductOption(ProductOptionInterface $option, string $value, string $code): ProductOptionValueInterface
    {
        /** @var ProductOptionValueInterface $optionValue */
        $optionValue = $this->productOptionValueFactory->createNew();

        $optionValue->setValue($value);
        $optionValue->setCode($code);
        $optionValue->setOption($option);

        $option->addValue($optionValue);

        return $optionValue;
    }

    /**
     * @param ProductInterface $product
     */
    private function saveProduct(ProductInterface $product): void
    {
        $this->productRepository->add($product);
        $this->sharedStorage->set('product', $product);
    }

    /**
     * @param int $price
     * @param ChannelInterface|null $channel
     *
     * @return ChannelPricingInterface
     */
    private function createChannelPricingForChannel(int $price, ChannelInterface $channel = null): ChannelPricingInterface
    {
        /** @var ChannelPricingInterface $channelPricing */
        $channelPricing = $this->channelPricingFactory->createNew();
        $channelPricing->setPrice($price);
        $channelPricing->setChannelCode($channel->getCode());

        return $channelPricing;
    }
}
