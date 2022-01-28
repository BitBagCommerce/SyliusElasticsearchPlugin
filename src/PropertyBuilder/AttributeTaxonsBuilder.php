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

final class AttributeTaxonsBuilder extends AbstractBuilder
{
    /** @var TaxonRepositoryInterface */
    protected $taxonRepository;

    /** @var string */
    private $taxonsProperty;

    /** @var array */
    private $excludedAttributes;

    public function __construct(
        TaxonRepositoryInterface $taxonRepository,
        string $taxonsProperty,
        array $excludedAttributes = []
    ) {
        $this->taxonRepository = $taxonRepository;
        $this->taxonsProperty = $taxonsProperty;
        $this->excludedAttributes = $excludedAttributes;
    }

    public function consumeEvent(PostTransformEvent $event): void
    {
        $documentAttribute = $event->getObject();

        if (!$documentAttribute instanceof AttributeInterface
            || in_array($documentAttribute->getCode(), $this->excludedAttributes)
        ) {
            return;
        }

        $taxons = $this->taxonRepository->getTaxonsByAttributeViaProduct($documentAttribute);
        $taxonCodes = [];

        foreach ($taxons as $taxon) {
            $taxonCodes[] = $taxon->getCode();
        }

        $document = $event->getDocument();

        $document->set($this->taxonsProperty, $taxonCodes);
    }
}
