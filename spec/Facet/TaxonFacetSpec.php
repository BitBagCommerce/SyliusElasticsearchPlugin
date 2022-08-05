<?php

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\Facet;

use BitBag\SyliusElasticsearchPlugin\Facet\FacetInterface;
use BitBag\SyliusElasticsearchPlugin\Facet\TaxonFacet;
use Elastica\Aggregation\Terms;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\Taxon;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;

final class TaxonFacetSpec extends ObjectBehavior
{
    private $taxonsProperty = 'product_taxons';

    public function let(TaxonRepositoryInterface $taxonRepository): void
    {
        $this->beConstructedWith($taxonRepository, $this->taxonsProperty);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(TaxonFacet::class);
    }

    public function it_implements_facet_interface(): void
    {
        $this->shouldHaveType(FacetInterface::class);
    }

    public function it_returns_taxon_terms_aggregation(): void
    {
        $expectedAggregation = new Terms('taxon');
        $expectedAggregation->setField('product_taxons.keyword');

        $this->getAggregation()->shouldBeLike($expectedAggregation);
    }

    public function it_returns_terms_query_for_selected_buckets(): void
    {
        $this->getQuery(['taxon_1', 'taxon_2'])->shouldBeLike(
            new \Elastica\Query\Terms('product_taxons.keyword', ['taxon_1', 'taxon_2'])
        );
    }

    public function it_returns_taxon_name_as_bucket_label(TaxonRepositoryInterface $taxonRepository): void
    {
        $taxon = new Taxon();
        $taxon->setCurrentLocale('en_US');
        $taxon->setName('Taxon 1');
        $taxonRepository->findOneBy(['code' => 'taxon_1'])->shouldBeCalled()->willReturn($taxon);

        $this->getBucketLabel(['key' => 'taxon_1', 'doc_count' => 3])->shouldBe('Taxon 1 (3)');
    }

    public function it_returns_bucket_key_as_bucket_label_if_taxon_could_not_be_found(
        TaxonRepositoryInterface $taxonRepository
    ): void {
        $taxonRepository->findOneBy(['code' => 'taxon_1'])->shouldBeCalled()->willReturn(null);

        $this->getBucketLabel(['key' => 'taxon_1', 'doc_count' => 3])->shouldBe('taxon_1 (3)');
    }
}
