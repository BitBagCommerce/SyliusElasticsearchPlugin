<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types = 1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use BitBag\SyliusElasticsearchPlugin\EntityRepository\TaxonRepository as BitbagTaxonRepository;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Attribute\Model\AttributeInterface;

final class AttributeTaxonsBuilder extends AbstractBuilder
{
    /**
     * @var BitbagTaxonRepository
     */
    protected $bitbagTaxonRepository;

    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @var array
     */
    private $excludedAttributes;

    /**
     * @param BitbagTaxonRepository $bitbagTaxonRepository
     * @param string                $taxonsProperty
     * @param array                 $excludedAttributes
     */
    public function __construct(
        BitbagTaxonRepository $bitbagTaxonRepository,
        string $taxonsProperty,
        array $excludedAttributes = []
    ) {
        $this->bitbagTaxonRepository = $bitbagTaxonRepository;
        $this->taxonsProperty        = $taxonsProperty;
        $this->excludedAttributes    = $excludedAttributes;
    }

    /**
     * @param TransformEvent $event
     */
    public function consumeEvent(TransformEvent $event): void
    {
        $documentAttribute = $event->getObject();

        if (!$documentAttribute instanceof AttributeInterface
            || in_array($documentAttribute->getCode(), $this->excludedAttributes)
        ) {
            return;
        }

        $taxons = $this->bitbagTaxonRepository->getTaxonsByAttributeViaProduct($documentAttribute);

        $taxonCodes = [];
        foreach ($taxons as $taxon) {
            $taxonCodes[] = $taxon->getCode();
        }

        $document = $event->getDocument();

        $document->set($this->taxonsProperty, $taxonCodes);
    }
}
