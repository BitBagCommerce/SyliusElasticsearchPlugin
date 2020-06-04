<?php

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

    /** @var string */
    private $taxonsPropertyName;

    /** @var TaxonRepositoryInterface */
    private $taxonRepository;

    public function __construct(TaxonRepositoryInterface $taxonRepository, string $taxonsPropertyName)
    {
        $this->taxonRepository = $taxonRepository;
        $this->taxonsPropertyName = $taxonsPropertyName;
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
