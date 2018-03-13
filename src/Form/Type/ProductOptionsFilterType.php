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

use BitBag\SyliusElasticsearchPlugin\Context\ProductOptionsContextInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductOptionsFilterType extends AbstractFilterType
{
    /**
     * @var ProductOptionsContextInterface
     */
    private $productOptionsContext;

    /**
     * @var ConcatedNameResolverInterface
     */
    private $optionNameResolver;

    /**
     * @param ProductOptionsContextInterface $productOptionsContext
     * @param ConcatedNameResolverInterface $optionNameResolver
     */
    public function __construct(
        ProductOptionsContextInterface $productOptionsContext,
        ConcatedNameResolverInterface $optionNameResolver
    ) {
        $this->productOptionsContext = $productOptionsContext;
        $this->optionNameResolver = $optionNameResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ProductOptionInterface $productOption */
        foreach ($this->productOptionsContext->getOptions() as $productOption) {
            $name = $this->optionNameResolver->resolvePropertyName($productOption->getCode());
            $optionValues = array_map(function (ProductOptionValueInterface $productOptionValue): ?string {
                return $productOptionValue->getValue();
            }, $productOption->getValues()->toArray());

            $builder->add($name, ChoiceType::class, [
                'label' => $productOption->getName(),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => array_combine($optionValues, $optionValues),
            ]);
        }
    }
}
