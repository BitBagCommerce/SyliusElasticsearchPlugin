<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AbstractBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\Mapper\ProductTaxonsMapperInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductTaxonsBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\PropertyBuilderInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use PhpSpec\ObjectBehavior;
use spec\BitBag\SyliusElasticsearchPlugin\EventMother;

final class ProductTaxonsBuilderSpec extends ObjectBehavior
{
    function let(ProductTaxonsMapperInterface $productTaxonsMapper): void
    {
        $this->beConstructedWith($productTaxonsMapper, 'taxons');
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductTaxonsBuilder::class);
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
