<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace PHPUnit\Integration\Form\Type\ChoiceMapper\AttributesMapper;

use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\AttributesMapper\AttributesTypeDateMapper;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductAttributeValueRepository;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository;
use Tests\BitBag\SyliusElasticsearchPlugin\Integration\IntegrationTestCase;

class AttributesTypeDateMapperTest extends IntegrationTestCase
{
    private ProductAttributeValueRepository $productAttributeValueRepository;

    private EntityRepository $attributeRepository;

    private TaxonRepository $taxonRepository;

    private AttributesTypeDateMapper $attributesTypeDateMapper;

    public function SetUp(): void
    {
        parent::SetUp();
        $container = self::getContainer();

        $this->productAttributeValueRepository = $container->get('bitbag.sylius_elasticsearch_plugin.repository.product_attribute_value_repository');
        $this->attributeRepository = $container->get('sylius.repository.product_attribute');
        $this->taxonRepository = $container->get('sylius.repository.taxon');
        $this->attributesTypeDateMapper = $container->get('bitbag_sylius_elasticsearch_plugin.form.mapper.type.date');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        self::ensureKernelShutdown();
    }

    public function test_date_mapper(): void
    {
        $this->loadFixturesFromFiles(['Type/ChoiceMapper/AttributesMapper/test_attributes_type_date_mapper.yaml']);

        $attribute = $this->attributeRepository->findAll()[0];
        $taxon = $this->taxonRepository->findAll()[0];

        $uniqueAttributeValues = $this->productAttributeValueRepository->getUniqueAttributeValues($attribute, $taxon);

        $result = $this->attributesTypeDateMapper->map($uniqueAttributeValues);

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
    }
}
