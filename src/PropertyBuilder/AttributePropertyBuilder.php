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

final class AttributePropertyBuilder implements PropertyBuilderInterface
{
    /**
     * @var string
     */
    private $attributePropertyPrefix;

    /**
     * @param string $attributePropertyPrefix
     */
    public function __construct(string $attributePropertyPrefix)
    {
        $this->attributePropertyPrefix = $attributePropertyPrefix;
    }

    /**
     * @param TransformEvent $event
     */
    public function buildProperty(TransformEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getObject();
        $document = $event->getDocument();

        $this->resolveProductAttributes($product, $document);
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
     * @param ProductInterface $product
     * @param Document $document
     */
    private function resolveProductAttributes(ProductInterface $product, Document $document): void
    {
        foreach ($product->getAttributes() as $attributeValue) {
            $attributeCode = $attributeValue->getAttribute()->getCode();
            $index = $this->attributePropertyPrefix . '_' . $attributeCode;

            if (!$document->has($index)) {
                $document->set($index, []);
            }

            $reference = $document->get($index);
            $reference[] = $attributeValue->getValue();

            $document->set($index, $reference);
        }
    }
}
