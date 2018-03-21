<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Service;

use FOS\ElasticaBundle\Event\IndexPopulateEvent;
use FOS\ElasticaBundle\Event\TypePopulateEvent;
use FOS\ElasticaBundle\Index\IndexManager;
use FOS\ElasticaBundle\Index\Resetter;
use FOS\ElasticaBundle\Persister\PagerPersisterInterface;
use FOS\ElasticaBundle\Persister\PagerPersisterRegistry;
use FOS\ElasticaBundle\Provider\PagerProviderRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class Populate
{
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var IndexManager
     */
    private $indexManager;

    /**
     * @var PagerProviderRegistry
     */
    private $pagerProviderRegistry;

    /**
     * @var PagerPersisterRegistry
     */
    private $pagerPersisterRegistry;

    /**
     * @var PagerPersisterInterface
     */
    private $pagerPersister;

    /**
     * @var Resetter
     */
    private $resetter;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param IndexManager $indexManager
     * @param PagerProviderRegistry $pagerProviderRegistry
     * @param PagerPersisterRegistry $pagerPersisterRegistry
     * @param Resetter $resetter
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        IndexManager $indexManager,
        PagerProviderRegistry $pagerProviderRegistry,
        PagerPersisterRegistry $pagerPersisterRegistry,
        Resetter $resetter
    ) {
        $this->dispatcher = $dispatcher;
        $this->indexManager = $indexManager;
        $this->pagerProviderRegistry = $pagerProviderRegistry;
        $this->pagerPersisterRegistry = $pagerPersisterRegistry;
        $this->resetter = $resetter;
    }

    public function populateIndex(): void
    {
        $this->pagerPersister = $this->pagerPersisterRegistry->getPagerPersister('in_place');

        $indexes = array_keys($this->indexManager->getAllIndexes());

        $options = [
            'delete' => true,
            'reset' => true,
        ];

        foreach ($indexes as $index) {
            $event = new IndexPopulateEvent($index, true, $options);
            $this->dispatcher->dispatch(IndexPopulateEvent::PRE_INDEX_POPULATE, $event);

            if ($event->isReset()) {
                $this->resetter->resetIndex($index, true);
            }

            $types = array_keys($this->pagerProviderRegistry->getIndexProviders($index));
            foreach ($types as $type) {
                $this->populateIndexType($index, $type, false, $event->getOptions());
            }

            $this->dispatcher->dispatch(IndexPopulateEvent::POST_INDEX_POPULATE, $event);

            $this->refreshIndex($index);
        }
    }

    /**
     * @param string $index
     * @param string $type
     * @param bool $reset
     * @param array $options
     */
    private function populateIndexType(string $index, string $type, bool $reset, array $options): void
    {
        $event = new TypePopulateEvent($index, $type, $reset, $options);
        $this->dispatcher->dispatch(TypePopulateEvent::PRE_TYPE_POPULATE, $event);

        if ($event->isReset()) {
            $this->resetter->resetIndexType($index, $type);
        }

        $provider = $this->pagerProviderRegistry->getProvider($index, $type);

        $pager = $provider->provide($options);

        $options['indexName'] = $index;
        $options['typeName'] = $type;

        $this->pagerPersister->insert($pager, $options);

        $this->dispatcher->dispatch(TypePopulateEvent::POST_TYPE_POPULATE, $event);

        $this->refreshIndex($index);
    }

    /**
     * @param string $index
     */
    private function refreshIndex(string $index): void
    {
        $this->indexManager->getIndex($index)->refresh();
    }
}
