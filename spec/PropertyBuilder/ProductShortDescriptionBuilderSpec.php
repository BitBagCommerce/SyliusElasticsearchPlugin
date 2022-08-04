<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AbstractBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductShortDescriptionBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\PropertyBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use PhpSpec\ObjectBehavior;

final class ProductShortDescriptionBuilderSpec extends ObjectBehavior
{
    public function let(ConcatedNameResolverInterface $productNameNameResolver): void
    {
        $this->beConstructedWith($productNameNameResolver);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductShortDescriptionBuilder::class);
        $this->shouldHaveType(AbstractBuilder::class);
    }

    public function it_implements_property_builder_interface(): void
    {
        $this->shouldHaveType(PropertyBuilderInterface::class);
    }

    public function it_consumes_event(Document $document, $object): void
    {
        $event = new PostTransformEvent($document->getWrappedObject(), [], $object->getWrappedObject());
        $this->consumeEvent($event);
    }
}
