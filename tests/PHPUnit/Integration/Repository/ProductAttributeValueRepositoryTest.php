<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Integration\Repository;

use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeValueRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository;
use Tests\BitBag\SyliusElasticsearchPlugin\Integration\IntegrationTestCase;

class ProductAttributeValueRepositoryTest extends IntegrationTestCase
{
    private ProductAttributeValueRepository $productAttributeValueRepository;

    private EntityRepository $attributeRepository;

    private TaxonRepository $taxonRepository;

    public function SetUp(): void
    {
        parent::SetUp();
        $container = self::getContainer();

        $this->productAttributeValueRepository = $container->get('bitbag.sylius_elasticsearch_plugin.repository.product_attribute_value_repository');
        $this->attributeRepository = $container->get('sylius.repository.product_attribute');
        $this->taxonRepository = $container->get('sylius.repository.taxon');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        self::ensureKernelShutdown();
    }

    public function test_get_unique_attribute_values(): void
    {
        $this->loadFixturesFromFiles(['Repository/ProductAttributeValueRepositoryTest/test_product_attribute_value_repository.yaml']);

        $attribute = $this->attributeRepository->findAll()[0];
        $taxon = $this->taxonRepository->findAll()[0];

        $result = $this->productAttributeValueRepository->getUniqueAttributeValues($attribute, $taxon);

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }

    public function test_get_unique_attribute_values_for_ancestor_taxon(): void
    {
        $this->loadFixturesFromFiles(['Repository/ProductAttributeValueRepositoryTest/test_product_attribute_value_repository_for_ancestor_taxon.yaml']);

        $attribute = $this->attributeRepository->findAll()[0];
        $taxon = $this->taxonRepository->findAll()[0];

        $result = $this->productAttributeValueRepository->getUniqueAttributeValues($attribute, $taxon);

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }
}
