<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Context\ProductOptionsContextInterface;
use BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\ProductOptionsMapperInterface;
use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/** @deprecated  */
final class ProductOptionsFilterType extends AbstractFilterType
{
    public function __construct(
        private ProductOptionsContextInterface $productOptionsContext,
        private ConcatedNameResolverInterface $optionNameResolver,
        private ProductOptionsMapperInterface $productOptionsMapper
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var array $options */
        $options = $this->productOptionsContext->getOptions();
        foreach ($options as $productOption) {
            $name = $this->optionNameResolver->resolvePropertyName($productOption->getCode());
            $choices = $this->productOptionsMapper->mapToChoices($productOption);
            $choices = array_unique($choices);

            $builder->add($name, ChoiceType::class, [
                'label' => $productOption->getName(),
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices' => $choices,
            ]);
        }
    }
}
