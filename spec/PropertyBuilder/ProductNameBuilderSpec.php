<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AbstractBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductNameBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\PropertyBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use PhpSpec\ObjectBehavior;

final class ProductNameBuilderSpec extends ObjectBehavior
{
    public function let(ConcatedNameResolverInterface $productNameNameResolver): void
    {
        $this->beConstructedWith($productNameNameResolver);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductNameBuilder::class);
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
