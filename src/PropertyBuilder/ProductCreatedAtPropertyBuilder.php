<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductCreatedAtPropertyBuilder extends AbstractBuilder
{
    /**
     * @var string
     */
    private $createdAtProperty;

    /**
     * @param string $createdAtProperty
     */
    public function __construct(string $createdAtProperty)
    {
        $this->createdAtProperty = $createdAtProperty;
    }

    /**
     * {@inheritdoc}
     */
    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $createdAt = (int) $product->getCreatedAt()->format('U');

                $document->set($this->createdAtProperty, $createdAt);
            }
        );
    }
}
