<?php

declare(strict_types=1);

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresherInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

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
