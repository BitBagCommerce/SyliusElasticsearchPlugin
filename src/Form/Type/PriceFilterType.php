<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolverInterface;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Type;

final class PriceFilterType extends AbstractFilterType
{
    /** @var PriceNameResolverInterface */
    private $priceNameResolver;

    /** @var CurrencyContextInterface */
    private $currencyContext;

    public function __construct(PriceNameResolverInterface $priceNameResolver, CurrencyContextInterface $currencyContext)
    {
        $this->priceNameResolver = $priceNameResolver;
        $this->currencyContext = $currencyContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add($this->priceNameResolver->resolveMinPriceName(), MoneyType::class, [
                'label' => 'bitbag_sylius_elasticsearch_plugin.ui.min_price',
                'required' => false,
                'currency' => $this->currencyContext->getCurrencyCode(),
                'constraints' => [
                    new Type([
                        'type' => 'numeric',
                        'message' => 'bitbag_sylius_elasticsearch_plugin.min_price_numeric',
                    ]),
                    new PositiveOrZero([
                        'message' => 'bitbag_sylius_elasticsearch_plugin.min_price_positive_or_zero',
                    ]),
                ],
            ])
            ->add($this->priceNameResolver->resolveMaxPriceName(), MoneyType::class, [
                'label' => 'bitbag_sylius_elasticsearch_plugin.ui.max_price',
                'required' => false,
                'currency' => $this->currencyContext->getCurrencyCode(),
                'constraints' => [
                    new Type([
                        'type' => 'numeric',
                        'message' => 'bitbag_sylius_elasticsearch_plugin.max_price_numeric',
                    ]),
                    new PositiveOrZero([
                        'message' => 'bitbag_sylius_elasticsearch_plugin.max_price_positive_or_zero',
                    ]),
                ],
            ])
        ;
    }
}
