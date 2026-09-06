<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusElasticsearchPlugin\Behat\Service;

use FOS\ElasticaBundle\Event\PostIndexPopulateEvent;
use FOS\ElasticaBundle\Event\PreIndexPopulateEvent;
use FOS\ElasticaBundle\Index\IndexManager;
use FOS\ElasticaBundle\Index\ResetterInterface;
use FOS\ElasticaBundle\Persister\PagerPersisterInterface;
use FOS\ElasticaBundle\Persister\PagerPersisterRegistry;
use FOS\ElasticaBundle\Provider\PagerProviderRegistry;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class Populate
{
    private PagerPersisterInterface $pagerPersister;

    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private IndexManager $indexManager,
        private PagerProviderRegistry $pagerProviderRegistry,
        private PagerPersisterRegistry $pagerPersisterRegistry,
        private ResetterInterface $resetter,
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
