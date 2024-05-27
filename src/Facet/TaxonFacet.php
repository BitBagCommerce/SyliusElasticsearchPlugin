<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Facet;

use Elastica\Aggregation\AbstractAggregation;
use Elastica\Aggregation\Terms;
use Elastica\Query\AbstractQuery;
use Elastica\Query\Terms as TermsQuery;
use Sylius\Component\Core\Model\Taxon;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class TaxonFacet implements FacetInterface
{
    public const FACET_ID = 'taxon';

    public function __construct(
        private TaxonRepositoryInterface $taxonRepository,
        private string $taxonsPropertyName
    ) {
    }

    public function getAggregation(): AbstractAggregation
    {
        $terms = new Terms(self::FACET_ID);
        $terms->setField($this->getField());

        return $terms;
    }

    public function getQuery(array $selectedBuckets): AbstractQuery
    {
        return new TermsQuery($this->getField(), $selectedBuckets);
    }

    public function getBucketLabel(array $bucket): string
    {
        $label = $taxonCode = $bucket['key'];
        $taxon = $this->taxonRepository->findOneBy(['code' => $taxonCode]);
        if ($taxon instanceof Taxon) {
            $label = $taxon->getName();
        }

        return sprintf('%s (%s)', $label, $bucket['doc_count']);
    }

    private function getField(): string
    {
        return $this->taxonsPropertyName . '.keyword';
    }

    public function getLabel(): string
    {
        return 'bitbag_sylius_elasticsearch_plugin.ui.facet.taxon.label';
    }
}
