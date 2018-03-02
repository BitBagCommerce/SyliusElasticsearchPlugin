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
use Sylius\Component\Core\Model\TaxonInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TaxonPropertyBuilder implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $taxonProperty;

    /**
     * @param string $taxonProperty
     */
    public function __construct(string $taxonProperty)
    {
        $this->taxonProperty = $taxonProperty;
    }

    /**
     * @param TransformEvent $event
     */
    public function addAttributeProperties(TransformEvent $event): void
    {
        /** @var ProductInterface $product */
        $product = $event->getObject();
        $document = $event->getDocument();

        $document->set($this->taxonProperty, []);
        $this->resolveProductTaxons($product->getTaxons(), $document);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TransformEvent::POST_TRANSFORM => 'addAttributeProperties',
        ];
    }

    /**
     * @param Collection|TaxonInterface[] $taxons
     * @param Document $document
     */
    private function resolveProductTaxons(Collection $taxons, Document $document): void
    {
        foreach ($taxons as $taxon) {
            $reference = $document->get($this->taxonProperty);
            $name = $taxon->getName();

            if (!in_array($name, $reference)) {
                $reference[] = $name;
                $document->set($this->taxonProperty, $reference);
            }

            if ($taxon->hasChildren()) {
                $this->resolveProductTaxons($taxon->getChildren(), $document);
            }
        }
    }
}
