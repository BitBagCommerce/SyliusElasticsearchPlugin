<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Service;


use FOS\ElasticaBundle\Event\PostIndexPopulateEvent;
use FOS\ElasticaBundle\Event\PreIndexPopulateEvent;
use FOS\ElasticaBundle\Index\IndexManager;
use FOS\ElasticaBundle\Index\Resetter;
use FOS\ElasticaBundle\Persister\PagerPersisterInterface;
use FOS\ElasticaBundle\Persister\PagerPersisterRegistry;
use FOS\ElasticaBundle\Provider\PagerProviderRegistry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class Populate
{
    /** @var EventDispatcherInterface */
    private $dispatcher;

    /** @var IndexManager */
    private $indexManager;

    /** @var PagerProviderRegistry */
    private $pagerProviderRegistry;

    /** @var PagerPersisterRegistry */
    private $pagerPersisterRegistry;

    /** @var PagerPersisterInterface */
    private $pagerPersister;

    /** @var Resetter */
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
            $event = new PreIndexPopulateEvent($index, true, $options);
            $this->dispatcher->dispatch($event);

            if ($event->isReset()) {
                $this->resetter->resetIndex($index, true);
            }

            $this->populateIndexType($index, false, $event->getOptions());

            $postEvent = new PostIndexPopulateEvent($index, true, $options);

            $this->dispatcher->dispatch($postEvent);

            $this->refreshIndex($index);
        }
    }

    /**
     * @param string $index
     * @param string $type
     * @param bool $reset
     * @param array $options
     */
    private function populateIndexType(string $index, bool $reset, array $options): void
    {
        $provider = $this->pagerProviderRegistry->getProvider($index);

        $pager = $provider->provide($options);

        $options['indexName'] = $index;

        $this->pagerPersister->insert($pager, $options);

        $this->refreshIndex($index);
    }

    private function refreshIndex(string $index): void
    {
        $this->indexManager->getIndex($index)->refresh();
    }
}
