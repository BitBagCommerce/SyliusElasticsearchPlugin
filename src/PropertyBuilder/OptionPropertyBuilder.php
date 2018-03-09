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

final class OptionPropertyBuilder extends AbstractPropertyBuilder
{
    /**
     * @var string
     */
    private $optionPropertyPrefix;

    /**
     * @param string $optionPropertyPrefix
     */
    public function __construct(string $optionPropertyPrefix)
    {
        $this->optionPropertyPrefix = $optionPropertyPrefix;
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

        $this->resolveProductOptions($product, $document);
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
                $index = $this->optionPropertyPrefix . '_' . $optionCode;

                if (!$document->has($index)) {
                    $document->set($index, []);
                }

                $reference = $document->get($index);
                $value = $productOptionValue->getValue();

                if (!in_array($value, $reference)) {
                    $reference[] = $productOptionValue->getValue();

                    $document->set($index, $reference);
                }
            }
        }
    }
}
