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

use BitBag\SyliusElasticsearchPlugin\Repository\TaxonRepositoryInterface;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;

final class AttributeTaxonsBuilder extends AbstractBuilder
{
    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
        private string $taxonsProperty,
        private bool $includeAllDescendants,
        private array $excludedAttributes = []
    ) {
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $documentAttribute = $event->getObject();

        if (!$documentAttribute instanceof AttributeInterface
            || !$documentAttribute instanceof ProductAttributeInterface
            || in_array($documentAttribute->getCode(), $this->excludedAttributes, true)
        ) {
            return;
        }

        $taxons = $this->taxonRepository->getTaxonsByAttributeViaProduct($documentAttribute);
        $taxonCodes = [];

        /** @var TaxonInterface $taxon */
        foreach ($taxons as $taxon) {
            $taxonCodes[] = $taxon->getCode();

            if (true === $this->includeAllDescendants) {
                foreach ($taxon->getAncestors() as $ancestor) {
                    $taxonCodes[] = $ancestor->getCode();
                }
            }
        }

        $document = $event->getDocument();

        $document->set($this->taxonsProperty, $taxonCodes);
    }
}
