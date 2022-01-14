<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);


namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresherInterface;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class ProductTaxonIndexListener
{
    /** @var ResourceRefresherInterface */
    private $resourceRefresher;

    /** @var ObjectPersisterInterface */
    private $objectPersister;

    public function __construct(ResourceRefresherInterface $resourceRefresher, ObjectPersisterInterface $objectPersister)
    {
        $this->resourceRefresher = $resourceRefresher;
        $this->objectPersister = $objectPersister;
    }

    public function updateIndex(ProductTaxonInterface $productTaxon): void
    {
        $this->resourceRefresher->refresh($productTaxon->getProduct(), $this->objectPersister);
    }
}
