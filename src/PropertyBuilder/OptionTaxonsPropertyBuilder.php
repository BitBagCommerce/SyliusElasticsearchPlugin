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
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;

final class OptionTaxonsPropertyBuilder implements PropertyBuilderInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var string
     */
    private $optionProperty;

    /**
     * @var string
     */
    private $productTaxonsProperty;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param string $optionProperty
     * @param string $productTaxonsProperty
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        string $optionProperty,
        string $productTaxonsProperty
    )
    {
        $this->productRepository = $productRepository;
        $this->optionProperty = $optionProperty;
        $this->productTaxonsProperty = $productTaxonsProperty;
    }

    /**
     * @param TransformEvent $event
     */
    public function buildProperty(TransformEvent $event): void
    {
        /** @var ProductOptionInterface $documentOptionValue */
        $documentProductOption = $event->getObject();
        $document = $event->getDocument();
        $products = $this->productRepository->findAll();
        $taxons = [];

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            foreach ($product->getVariants() as $productVariant) {
                foreach ($productVariant->getOptionValues() as $productOptionValue) {
                    if ($documentProductOption === $productOptionValue->getOption()) {
                        foreach ($product->getTaxons() as $taxon) {
                            $code = $taxon->getCode();
                            if (!in_array($code, $taxons)) {
                                $taxons[] = $code;
                            }
                        }
                    }
                }
            }
        }

        $document->set($this->productTaxonsProperty, $taxons);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            TransformEvent::POST_TRANSFORM => 'buildProperty',
        ];
    }
}
