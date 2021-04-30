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
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductTaxonContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var FactoryInterface */
    private $productTaxonFactory;

    /** @var EntityManagerInterface */
    private $objectManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        FactoryInterface $productTaxonFactory,
        EntityManagerInterface $objectManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->productTaxonFactory = $productTaxonFactory;
        $this->objectManager = $objectManager;
    }

    /**
     * @Given /^these products belongs to ("[^"]+" taxon)$/
     */
    public function theseProductsBelongsToTaxon(TaxonInterface $taxon): void
    {
        /** @var ProductInterface $product */
        foreach ($this->sharedStorage->get('products') as $product) {
            $productTaxon = $this->createProductTaxon($taxon, $product);

            $product->addProductTaxon($productTaxon);

            $this->objectManager->persist($product);
        }

        $this->objectManager->flush();
    }

    /**
     * @Given /^these products belongs primarily to ("[^"]+" taxon)$/
     */
    public function theseProductsBelongsPrimarilyToTaxon(TaxonInterface $taxon): void
    {
        /** @var ProductInterface $product */
        foreach ($this->sharedStorage->get('products') as $product) {
            $productTaxon = $this->createProductTaxon($taxon, $product);

            $product->setMainTaxon($productTaxon->getTaxon());

            $this->objectManager->persist($product);
        }

        $this->objectManager->flush();
    }

    private function createProductTaxon(
        TaxonInterface $taxon,
        ProductInterface $product,
        int $position = null
    ): ProductTaxonInterface {
        /** @var ProductTaxonInterface $productTaxon */
        $productTaxon = $this->productTaxonFactory->createNew();
        $productTaxon->setProduct($product);
        $productTaxon->setTaxon($taxon);

        if (null !== $position) {
            $productTaxon->setPosition($position);
        }

        return $productTaxon;
    }
}
