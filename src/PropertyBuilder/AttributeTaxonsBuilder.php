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

use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

final class AttributeTaxonsBuilder extends AbstractBuilder
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var string
     */
    private $attributeProperty;

    /**
     * @var string
     */
    private $taxonsProperty;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param string $attributeProperty
     * @param string $taxonsProperty
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        string $attributeProperty,
        string $taxonsProperty
    )
    {
        $this->productRepository = $productRepository;
        $this->attributeProperty = $attributeProperty;
        $this->taxonsProperty = $taxonsProperty;
    }

    /**
     * @param TransformEvent $event
     */
    public function buildProperty(TransformEvent $event): void
    {
        /** @var AttributeInterface $documentAttribute */
        $documentAttribute = $event->getObject();

        if (!$documentAttribute instanceof AttributeInterface) {
            return;
        }

        $document = $event->getDocument();
        $products = $this->productRepository->findAll();
        $taxons = [];

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            foreach ($product->getAttributes() as $attributeValue) {
                if ($documentAttribute === $attributeValue->getAttribute()) {
                    foreach ($product->getTaxons() as $taxon) {
                        $code = $taxon->getCode();
                        if (!in_array($code, $taxons)) {
                            $taxons[] = $code;
                        }
                    }
                }
            }
        }

        $document->set($this->taxonsProperty, $taxons);
    }
}
