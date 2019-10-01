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
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class ResourceIndexListener
{
    /** @var ResourceRefresherInterface */
    private $resourceRefresher;

    /** @var array */
    private $persistersMap;

    public function __construct(ResourceRefresherInterface $resourceRefresher, array $persistersMap)
    {
        $this->resourceRefresher = $resourceRefresher;
        $this->persistersMap = $persistersMap;
    }

    public function updateIndex(GenericEvent $event): void
    {
        $resource = $event->getSubject();

        Assert::isInstanceOf($resource, ResourceInterface::class);

        foreach ($this->persistersMap as $objectPersisterId => $modelClass) {
            if ($resource instanceof $modelClass) {
                $this->resourceRefresher->refresh($resource, $objectPersisterId);
            }
        }
    }
}
