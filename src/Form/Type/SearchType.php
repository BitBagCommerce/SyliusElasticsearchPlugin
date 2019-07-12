<?php
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Form\Type;

use BitBag\SyliusElasticsearchPlugin\Model\Box;
use BitBag\SyliusElasticsearchPlugin\Model\FacetsConfig;
use BitBag\SyliusElasticsearchPlugin\Model\Search;
use Elastica\Aggregation\Histogram;
use Elastica\Aggregation\Terms;
use Elastica\Query;
use Elastica\Query\MultiMatch;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use Pagerfanta\Adapter\AdapterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    /**
     * @var PaginatedFinderInterface
     */
    private $finder;

    public function __construct(PaginatedFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('box', SearchBoxType::class, ['label' => false])
        ;

        $formModifier = function (FormInterface $form, AdapterInterface $adapter) {
            if (!$adapter instanceof FantaPaginatorAdapter) {
                return;
            }

            $form->add('facets', SearchFacetsType::class, ['facets' => $adapter->getAggregations(), 'label' => false]);
        };

        $builder
            ->get('box')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    /** @var Box $data */
                    $data = $event->getForm()->getData();

                    if (!$data->getQuery()) {
                        return;
                    }

                    $multiMatch = new MultiMatch();
                    $multiMatch->setQuery($data->getQuery());
                    // TODO set search fields here (pay attention to locale-contex field, like name): $query->setFields([]);
                    $multiMatch->setFuzziness('AUTO');
                    $query = new Query($multiMatch);

                    foreach (FacetsConfig::get() as $key => $facet) {
                        $facetType = $facet['type'];
                        if ($facetType === 'histogram') {
                            $histogramAggregation = new Histogram(
                                $key,
                                $facet['options']['field'],
                                $facet['options']['interval']
                            );
                            if (isset($facet['options']['min_doc_count'])) {
                                $histogramAggregation->setMinimumDocumentCount($facet['options']['min_doc_count']);
                            }
                            $query->addAggregation($histogramAggregation);
                        } elseif ($facetType === 'terms') {
                            $termsAggregation = new Terms($key);
                            $termsAggregation->setField($facet['options']['field']);
                            $query->addAggregation($termsAggregation);
                        } else {
                            throw new \RuntimeException("Unknown facet type '{$facetType}'.");
                        }
                    }
                    $query->setSize(0);

                    $results = $this->finder->findPaginated($query);

                    if ($results->getAdapter()) {
                        $formModifier($event->getForm()->getParent(), $results->getAdapter());
                    }
                }
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return 'bitbag_elasticsearch_search';
    }
}
