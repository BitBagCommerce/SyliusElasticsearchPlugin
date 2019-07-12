<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Model\FacetsConfig;
use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;
use Sylius\Component\Currency\Context\CurrencyContextInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFacetsType extends AbstractType
{
    /**
     * @var MoneyFormatterInterface
     */
    private $moneyFormatter;
    /**
     * @var CurrencyContextInterface
     */
    private $currencyContext;
    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    public function __construct(
        MoneyFormatterInterface $moneyFormatter,
        CurrencyContextInterface $currencyContext,
        LocaleContextInterface $localeContext
    ) {
        $this->moneyFormatter = $moneyFormatter;
        $this->currencyContext = $currencyContext;
        $this->localeContext = $localeContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['facets'] as $facetKey => $facet) {
            $facetsConfig = FacetsConfig::get();
            if (!array_key_exists($facetKey, $facetsConfig)) {
                throw new \RuntimeException("Unkown configuration for facet with key '$facetKey'.");
            }
            $facetConfig = $facetsConfig[$facetKey];
            $facetType = $facetConfig['type'];
            $choices = [];
            foreach ($facet['buckets'] as $bucket) {
                if ($facetType === 'histogram') {
                    // TODO move money format elsewhere because histogram could be for a different attribute (i.e. date)
                    $from = $this->moneyFormatter->format(
                        (int)$bucket['key'],
                        $this->currencyContext->getCurrencyCode(),
                        $this->localeContext->getLocaleCode()
                    );
                    $to = $this->moneyFormatter->format(
                        (int)($bucket['key'] + $facetConfig['options']['interval']),
                        $this->currencyContext->getCurrencyCode(),
                        $this->localeContext->getLocaleCode()
                    );
                    $label = $from . ' - ' . $to . " ({$bucket['doc_count']})";
                    $choices[$label] = $bucket['key'];
                } elseif ($facetType === 'terms') {
                    $choices[$bucket['key'] . " ({$bucket['doc_count']})"] = $bucket['key'];
                } else {
                    throw new \RuntimeException("Unknown facet type '{$facetType}'.");
                }
            }
            $builder
                ->add(
                    $facetKey,
                    ChoiceType::class,
                    [
                        'label' => $facetConfig['label'], // TODO translate?
                        'choices' => $choices,
                        'expanded' => true,
                        'multiple' => true,
                    ]
                )
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('facets');
    }
}
