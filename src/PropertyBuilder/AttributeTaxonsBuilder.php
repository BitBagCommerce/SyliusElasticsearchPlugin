<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
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
    /** @var TaxonRepositoryInterface */
    protected $taxonRepository;

    /** @var string */
    private $taxonsProperty;

    /** @var array */
    private $excludedAttributes;

    /** @var bool */
    private $includeAllDescendants;

    public function __construct(
        TaxonRepositoryInterface $taxonRepository,
        string $taxonsProperty,
        bool $includeAllDescendants,
        array $excludedAttributes = []
    ) {
        $this->taxonRepository = $taxonRepository;
        $this->taxonsProperty = $taxonsProperty;
        $this->includeAllDescendants = $includeAllDescendants;
        $this->excludedAttributes = $excludedAttributes;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $documentAttribute = $event->getObject();

        if (!$documentAttribute instanceof AttributeInterface
            || !$documentAttribute instanceof ProductAttributeInterface
            || in_array($documentAttribute->getCode(), $this->excludedAttributes)
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
