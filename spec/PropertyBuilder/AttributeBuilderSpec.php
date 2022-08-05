<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AbstractBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AttributeBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\PropertyBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;

final class AttributeBuilderSpec extends ObjectBehavior
{
    function let(
        ConcatedNameResolverInterface $attributeNameResolver,
        StringFormatterInterface $stringFormatter,
        LocaleContextInterface $localeContext
    ): void {
        $this->beConstructedWith(
            $attributeNameResolver,
            $stringFormatter,
            $localeContext
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(AttributeBuilder::class);
        $this->shouldHaveType(AbstractBuilder::class);
    }

    function it_implements_property_builder_interface(): void
    {
        $this->shouldHaveType(PropertyBuilderInterface::class);
    }

    function it_consumes_event(Document $document, $object): void
    {
        $event = new PostTransformEvent($document->getWrappedObject(), [], $object->getWrappedObject());
        $this->consumeEvent($event);
    }
}
