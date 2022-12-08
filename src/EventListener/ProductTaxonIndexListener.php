<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresherInterface;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;

final class ProductTaxonIndexListener
{
    private ResourceRefresherInterface $resourceRefresher;

    private ObjectPersisterInterface $objectPersister;

    public function __construct(
        ResourceRefresherInterface $resourceRefresher,
        ObjectPersisterInterface $objectPersister
    ) {
        $this->resourceRefresher = $resourceRefresher;
        $this->objectPersister = $objectPersister;
    }

    public function updateIndex(ProductTaxonInterface $productTaxon): void
    {
        $this->resourceRefresher->refresh($productTaxon->getProduct(), $this->objectPersister);
    }
}
