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

final class OptionPropertiesListener implements EventSubscriberInterface
{
    /**
     * @param TransformEvent $event
     */
    public function addOptionProperties(TransformEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getObject();
        $document = $event->getDocument();

        $this->resolveProductOptions($product, $document);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TransformEvent::POST_TRANSFORM => 'addOptionProperties',
        ];
    }

    /**
     * @param ProductInterface $product
     * @param Document $document
     */
    private function resolveProductOptions(ProductInterface $product, Document $document): void
    {
        foreach ($product->getVariants() as $productVariant) {
            foreach ($productVariant->getOptionValues() as $productOptionValue) {
                $optionCode = $productOptionValue->getOption()->getCode();
                $index = 'option_' . $optionCode;

                if (!$document->has($index)) {
                    $document->set($index, []);
                }

                $reference = $document->get($index);
                $reference[] = $productOptionValue->getValue();

                $document->set($index, $reference);
            }
        }
    }
}
