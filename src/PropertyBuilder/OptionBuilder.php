<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    /** @var ConcatedNameResolverInterface */
    private $optionNameResolver;

    /** @var StringFormatterInterface */
    private $stringFormatter;

    public function __construct(ConcatedNameResolverInterface $optionNameResolver, StringFormatterInterface $stringFormatter)
    {
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
