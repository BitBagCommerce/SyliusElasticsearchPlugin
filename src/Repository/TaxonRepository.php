<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface as BaseTaxonRepositoryInterface;

class TaxonRepository implements TaxonRepositoryInterface
{
    /** @var BaseTaxonRepositoryInterface|EntityRepository */
    private $baseTaxonRepository;

    /** @var ProductRepositoryInterface|EntityRepository */
    private $productRepository;

    /** @var string */
    private $productTaxonEntityClass;

    /** @var string */
    private $productAttributeEntityClass;

    public function __construct(
        BaseTaxonRepositoryInterface $baseTaxonRepository,
        ProductRepositoryInterface $productRepository,
        string $productTaxonEntityClass,
        string $productAttributeEntityClass
    ) {
        $this->baseTaxonRepository = $baseTaxonRepository;
        $this->productRepository = $productRepository;
        $this->productTaxonEntityClass = $productTaxonEntityClass;
        $this->productAttributeEntityClass = $productAttributeEntityClass;
    }

    public function getTaxonsByAttributeViaProduct(AttributeInterface $attribute): array
    {
        return $this->baseTaxonRepository
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
            ->getResult()
        ;
    }
}
