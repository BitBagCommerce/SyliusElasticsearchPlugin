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

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class OptionBuilder extends AbstractBuilder
{
    private ConcatedNameResolverInterface $optionNameResolver;

    private StringFormatterInterface $stringFormatter;

    public function __construct(
        ConcatedNameResolverInterface $optionNameResolver,
        StringFormatterInterface $stringFormatter
    ) {
        $this->optionNameResolver = $optionNameResolver;
        $this->stringFormatter = $stringFormatter;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $this->buildProperty(
            $event,
            ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $this->resolveProductOptions($product, $document);
            }
        );
    }

    private function resolveProductOptions(ProductInterface $product, Document $document): void
    {
        foreach ($product->getVariants() as $productVariant) {
            foreach ($productVariant->getOptionValues() as $productOptionValue) {
                $optionCode = $productOptionValue->getOption()->getCode();
                $index = $this->optionNameResolver->resolvePropertyName($optionCode);
                $options = $document->has($index) ? $document->get($index) : [];
                $value = $this->stringFormatter->formatToLowercaseWithoutSpaces($productOptionValue->getValue());
                $options[] = $value;

                $document->set($index, array_values(array_unique($options)));
            }
        }
    }
}
