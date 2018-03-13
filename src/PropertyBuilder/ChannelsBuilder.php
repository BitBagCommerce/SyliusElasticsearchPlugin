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

use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class ChannelsBuilder extends AbstractBuilder
{
    /**
     * @var string
     */
    private $channelsProperty;

    /**
     * @param string $channelsProperty
     */
    public function __construct(string $channelsProperty)
    {
        $this->channelsProperty = $channelsProperty;
    }

    /**
     * @param TransformEvent $event
     */
    public function buildProperty(TransformEvent $event): void
    {
        $product = $event->getObject();

        if (!$product instanceof ProductInterface) {
            return;
        }

        $channels = [];

        /** @var ProductInterface $product */
        foreach ($product->getChannels() as $channel) {
            $channels[] = $channel->getCode();
        }

        $event->getDocument()->set($this->channelsProperty, $channels);
    }
}
