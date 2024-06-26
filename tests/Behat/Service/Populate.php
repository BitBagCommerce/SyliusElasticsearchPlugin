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
use FOS\ElasticaBundle\Index\ResetterInterface;
use FOS\ElasticaBundle\Persister\PagerPersisterRegistry;
use FOS\ElasticaBundle\Provider\PagerProviderRegistry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class Populate
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private IndexManager $indexManager,
        private PagerProviderRegistry $pagerProviderRegistry,
        private PagerPersisterRegistry $pagerPersisterRegistry,
        private ResetterInterface $resetter
    ) {
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

            $provider = $this->pagerProviderRegistry->getProvider($index);

            $pager = $provider->provide($options);
            $options['indexName'] = $index;

            $this->pagerPersister->insert($pager, $options);

            $event = new PostIndexPopulateEvent($index, true, $options);
            $this->dispatcher->dispatch($event);

            $this->refreshIndex($index);
        }
    }

    private function refreshIndex(string $index): void
    {
        $this->indexManager->getIndex($index)->refresh();
    }
}
