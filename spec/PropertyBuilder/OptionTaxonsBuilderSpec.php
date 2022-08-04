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
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\OptionTaxonsBuilder;
use BitBag\SyliusElasticsearchPlugin\PropertyBuilder\PropertyBuilderInterface;
use BitBag\SyliusElasticsearchPlugin\Repository\ProductVariantRepositoryInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class OptionTaxonsBuilderSpec extends ObjectBehavior
{
    public function let(
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

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(OptionTaxonsBuilder::class);
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
