<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Integration\Repository;

use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeRepository;
use Tests\BitBag\SyliusElasticsearchPlugin\Integration\IntegrationTestCase;

class ProductAttributeRepositoryTest extends IntegrationTestCase
{
    /** @var ProductAttributeRepository */
    private $attributeRepository;

    public function SetUp(): void
    {
        parent::SetUp();

        $this->attributeRepository = self::$container->get('bitbag.sylius_elasticsearch_plugin.repository.product_attribute_repository');
    }

    public function test_get_attribute_type_by_name(): void
    {
        $this->loadFixturesFromFiles(['Repository/ProductAttributeValueRepositoryTest/test_product_attribute_repository.yaml']);

        $result = $this->attributeRepository->getAttributeTypeByName('t_shirt_brand');

        $this->assertNotEmpty($result);
        $this->assertIsString($result);
    }
}
