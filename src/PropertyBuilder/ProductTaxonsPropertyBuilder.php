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

final class ProductTaxonsPropertyBuilder extends AbstractPropertyBuilder
{
    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @param string $taxonsProperty
     */
    public function __construct(string $taxonsProperty)
    {
        $this->taxonsProperty = $taxonsProperty;
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

        $taxons = [];

        /** @var ProductInterface $product */
        foreach ($product->getTaxons() as $taxon) {
            $taxons[] = $taxon->getCode();
        }

        $event->getDocument()->set($this->taxonsProperty, $taxons);
    }
}
