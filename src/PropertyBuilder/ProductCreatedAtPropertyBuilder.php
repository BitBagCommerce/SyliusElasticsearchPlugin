<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Webmozart\Assert\Assert;

final class ProductCreatedAtPropertyBuilder extends AbstractBuilder
{
    public function __construct(
        private string $createdAtProperty
    ) {
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                Assert::notNull($product->getCreatedAt());
                $createdAt = (int) $product->getCreatedAt()->format('U');

                $document->set($this->createdAtProperty, $createdAt);
            }
        );
    }
}
