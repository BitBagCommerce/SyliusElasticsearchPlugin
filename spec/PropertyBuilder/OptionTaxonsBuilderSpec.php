<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\Repository\ProductVariantRepositoryInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AbstractBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapperInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\OptionTaxonsBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\PropertyBuilderInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use PhpSpec\ObjectBehavior;
use spec\BitBag\SyliusElasticsearchPlugin\EventMother;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OptionTaxonsBuilderSpec extends ObjectBehavior
{
    function let(
        RepositoryInterface $productOptionValueRepository,
        ProductVariantRepositoryInterface $productVariantRepository,
        ProductTaxonsMapperInterface $productTaxonsMapper
    ): void {
        $this->beConstructedWith(
            $productOptionValueRepository,
            $productVariantRepository,
            $productTaxonsMapper,
            'taxons'
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OptionTaxonsBuilder::class);
        $this->shouldHaveType(AbstractBuilder::class);
    }

    function it_implements_property_builder_interface(): void
    {
        $this->shouldHaveType(PropertyBuilderInterface::class);
    }

    function it_consumes_event(Document $document): void
    {
        $this->consumeEvent(EventMother::createPostTransformEvent($document));
    }
}
