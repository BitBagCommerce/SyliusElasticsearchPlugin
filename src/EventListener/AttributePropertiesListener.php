<?php

/*
 * This file has been created by developers from BitBag. 
 * Feel free to contact us once you face any issues or want to start
 * another great project. 
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl. 
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AttributePropertiesListener implements EventSubscriberInterface
{
    /**
     * @param TransformEvent $event
     */
    public function addAttributeProperties(TransformEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getObject();
        $document = $event->getDocument();

        $this->resolveProductAttributes($product, $document);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            TransformEvent::POST_TRANSFORM => 'addAttributeProperties',
        ];
    }

    /**
     * @param ProductInterface $product
     * @param Document $document
     */
    private function resolveProductAttributes(ProductInterface $product, Document $document): void
    {
        foreach ($product->getAttributes() as $attributeValue) {
            $attributeCode = $attributeValue->getAttribute()->getCode();
            $index = 'attribute_' . $attributeCode;

            if (!$document->has($index)) {
                $document->set($index, []);
            }

            $reference = $document->get($index);
            $reference[] = $attributeValue->getValue();

            $document->set($index, $reference);
        }
    }
}
