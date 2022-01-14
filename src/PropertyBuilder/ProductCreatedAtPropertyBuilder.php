<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductCreatedAtPropertyBuilder extends AbstractBuilder
{
    /** @var string */
    private $createdAtProperty;

    public function __construct(string $createdAtProperty)
    {
        $this->createdAtProperty = $createdAtProperty;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $createdAt = (int) $product->getCreatedAt()->format('U');

                $document->set($this->createdAtProperty, $createdAt);
            }
        );
    }
}
