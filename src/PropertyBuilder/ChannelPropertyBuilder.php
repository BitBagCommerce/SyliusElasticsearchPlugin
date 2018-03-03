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

use Doctrine\Common\Collections\Collection;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class ChannelPropertyBuilder implements PropertyBuilderInterface
{
    /**
     * @var string
     */
    private $channelProperty;

    /**
     * @param string $channelProperty
     */
    public function __construct(string $channelProperty)
    {
        $this->channelProperty = $channelProperty;
    }

    /**
     * @param TransformEvent $event
     */
    public function buildProperty(TransformEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getObject();

        if (!$product instanceof ProductInterface) {
            return;
        }

        $document = $event->getDocument();

        $document->set($this->channelProperty, []);
        $this->resolveProductChannels($product->getChannels(), $document);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TransformEvent::POST_TRANSFORM => 'buildProperty',
        ];
    }

    /**
     * @param Collection|ChannelInterface[] $channels
     * @param Document $document
     */
    private function resolveProductChannels(Collection $channels, Document $document): void
    {
        foreach ($channels as $channel) {
            $reference = $document->get($this->channelProperty);
            $code = $channel->getCode();

            if (!in_array($code, $reference)) {
                $reference[] = $code;
                $document->set($this->channelProperty, $reference);
            }
        }
    }
}
