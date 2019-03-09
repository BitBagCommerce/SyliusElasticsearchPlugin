<?php

declare(strict_types = 1);

namespace BitBag\SyliusElasticsearchPlugin\EntityRepository;

use Doctrine\ORM\Query\Expr\Join;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface as BaseTaxonRepositoryInterface;

class TaxonRepository implements TaxonRepositoryInterface
{
    /**
     * @var BaseTaxonRepository
     */
    protected $taxonRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var string
     */
    protected $productTaxonEntityClass;

    /**
     * @var string
     */
    protected $productAttributeEntityClass;

    /**
     * TaxonRepository constructor.
     *
     * @param BaseTaxonRepositoryInterface $taxonRepository
     * @param ProductRepositoryInterface   $productRepository
     * @param string                       $productTaxonEntityClass
     * @param string                       $productAttributeEntityClass
     */
    public function __construct(
        BaseTaxonRepositoryInterface $taxonRepository,
        ProductRepositoryInterface $productRepository,
        string $productTaxonEntityClass,
        string $productAttributeEntityClass
    ) {
        $this->taxonRepository             = $taxonRepository;
        $this->productRepository           = $productRepository;
        $this->productTaxonEntityClass     = $productTaxonEntityClass;
        $this->productAttributeEntityClass = $productAttributeEntityClass;
    }

    /**
     * @param AttributeInterface $attribute
     *
     * @return array|TaxonInterface[]
     */
    public function getTaxonsByAttributeViaProduct(AttributeInterface $attribute): array
    {
        return $this->taxonRepository
            ->createQueryBuilder('t')
            ->distinct(true)
            ->select('t')
            ->leftJoin($this->productTaxonEntityClass, 'pt', Join::WITH, 'pt.taxon = t.id')
            ->where(
                'pt.product IN(' .
                $this
                    ->productRepository->createQueryBuilder('p')
                    ->leftJoin($this->productAttributeEntityClass, 'pav', Join::WITH, 'pav.subject = p.id')
                    ->where('pav.attribute = :attribute')
                    ->getQuery()
                    ->getDQL()
                . ')'
            )
            ->setParameter(':attribute', $attribute)
            ->getQuery()
            ->getResult();
    }

}
