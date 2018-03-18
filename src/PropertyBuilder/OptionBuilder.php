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

use BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatterInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class OptionBuilder extends AbstractBuilder
{
    /**
     * @var ConcatedNameResolverInterface
     */
    private $optionNameResolver;

    /**
     * @var StringFormatterInterface
     */
    private $stringFormatter;

    /**
     * @param ConcatedNameResolverInterface $optionNameResolver
     * @param StringFormatterInterface $stringFormatter
     */
    public function __construct(ConcatedNameResolverInterface $optionNameResolver, StringFormatterInterface $stringFormatter)
    {
        $this->optionNameResolver = $optionNameResolver;
        $this->stringFormatter = $stringFormatter;
    }

    /**
     * @param TransformEvent $event
     */
    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $this->resolveProductOptions($product, $document);
            });
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
                $index = $this->optionNameResolver->resolvePropertyName($optionCode);
                $options = $document->has($index) ? $document->get($index) : [];
                $value = $this->stringFormatter->formatToLowercaseWithoutSpaces($productOptionValue->getValue());
                $options[] = $value;

                $document->set($index, array_values(array_unique($options)));
            }
        }
    }
}
