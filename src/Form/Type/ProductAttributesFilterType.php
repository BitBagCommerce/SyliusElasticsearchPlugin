<?php

/*
 * This file has been created by developers from BitBag. 
 * Feel free to contact us once you face any issues or want to start
 * another great project. 
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl. 
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Context\ProductAttributesContextInterface;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductAttributeValueInterface;
use Sylius\Component\Product\Repository\ProductAttributeValueRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductAttributesFilterType extends AbstractFilterType
{
    /**
     * @var ProductAttributesContextInterface
     */
    private $productAttributesContext;

    /**
     * @var ProductAttributeValueRepositoryInterface
     */
    private $productAttributeValueRepository;

    /**
     * @var string
     */
    private $attributePropertyPrefix;

    /**
     * @param ProductAttributesContextInterface $productAttributesContext
     * @param ProductAttributeValueRepositoryInterface $productAttributeValueRepository
     * @param string $attributePropertyPrefix
     */
    public function __construct(
        ProductAttributesContextInterface $productAttributesContext,
        ProductAttributeValueRepositoryInterface $productAttributeValueRepository,
        string $attributePropertyPrefix
    )
    {
        $this->productAttributesContext = $productAttributesContext;
        $this->productAttributeValueRepository = $productAttributeValueRepository;
        $this->attributePropertyPrefix = $attributePropertyPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $attributes): void
    {
        /** @var ProductAttributeInterface $productAttribute */
        foreach ($this->productAttributesContext->getAttributes() as $productAttribute) {
            $name = $this->attributePropertyPrefix . '_' . $productAttribute->getCode();
            $attributeValues = array_map(function (?ProductAttributeValueInterface $productAttributeValue): ?string {
                return $productAttributeValue ? $productAttributeValue->getValue(): null;
            }, $this->productAttributeValueRepository->findBy(['attribute' => $productAttribute]));

            $builder->add($name, ChoiceType::class, [
                'label' => $productAttribute->getName(),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => array_combine($attributeValues, $attributeValues),
            ]);
        }
    }
}
