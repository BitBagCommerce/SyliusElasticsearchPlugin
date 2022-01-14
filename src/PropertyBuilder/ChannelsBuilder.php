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

final class ChannelsBuilder extends AbstractBuilder
{
    /** @var string */
    private $channelsProperty;

    public function __construct(string $channelsProperty)
    {
        $this->channelsProperty = $channelsProperty;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $channels = [];

                foreach ($product->getChannels() as $channel) {
                    $channels[] = $channel->getCode();
                }

                $document->set($this->channelsProperty, $channels);
            }
        );
    }
}
