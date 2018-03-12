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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

final class PriceFilterType extends AbstractFilterType
{
    /**
     * @var PriceNameResolverInterface
     */
    private $priceNameResolver;

    /**
     * @param PriceNameResolverInterface $priceNameResolver
     */
    public function __construct(PriceNameResolverInterface $priceNameResolver)
    {
        $this->priceNameResolver = $priceNameResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->priceNameResolver->resolveMinPrice(), IntegerType::class)
            ->add($this->priceNameResolver->resolveMaxPrice(), IntegerType::class)
        ;
    }
}
