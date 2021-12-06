<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresherInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;

final class ProductTaxonIndexListener
{
    /** @var ResourceRefresherInterface */
    private $resourceRefresher;

    /** @var string */
    private $objectPersisterId;

    public function __construct(ResourceRefresherInterface $resourceRefresher, string $objectPersisterId)
    {
        $this->resourceRefresher = $resourceRefresher;
        $this->objectPersisterId = $objectPersisterId;
    }

    public function updateIndex(ProductTaxonInterface $productTaxon): void
    {
        $this->resourceRefresher->refresh($productTaxon->getProduct(), $this->objectPersisterId);
    }
}
