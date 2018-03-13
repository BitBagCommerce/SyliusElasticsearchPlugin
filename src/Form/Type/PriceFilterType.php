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

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolverInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class PriceFilterType extends AbstractFilterType
{
    /**
     * @var PriceNameResolverInterface
     */
    private $priceNameResolver;

    /**
     * @var DataTransformerInterface
     */
    private $moneyTransformer;

    /**
     * @param PriceNameResolverInterface $priceNameResolver
     * @param DataTransformerInterface $moneyTransformer
     */
    public function __construct(PriceNameResolverInterface $priceNameResolver, DataTransformerInterface $moneyTransformer)
    {
        $this->priceNameResolver = $priceNameResolver;
        $this->moneyTransformer = $moneyTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->priceNameResolver->resolveMinPriceName(), IntegerType::class, ['attr' => ['value' => 100]])
            ->add($this->priceNameResolver->resolveMaxPriceName(), IntegerType::class, ['attr' => ['value' => 1500]])
        ;

        $builder->get($this->priceNameResolver->resolveMinPriceName())->addViewTransformer($this->moneyTransformer);
        $builder->get($this->priceNameResolver->resolveMaxPriceName())->addViewTransformer($this->moneyTransformer);
    }
}
