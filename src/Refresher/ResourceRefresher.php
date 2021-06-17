<?php

declare(strict_types=1);

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

namespace BitBag\SyliusElasticsearchPlugin\Refresher;

use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webmozart\Assert\Assert;

final class ResourceRefresher implements ResourceRefresherInterface
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function refresh(ResourceInterface $resource, string $objectPersisterId): void
    {
        /** @var ObjectPersisterInterface $objectPersister */
        $objectPersister = $this->container->get($objectPersisterId);
        Assert::isInstanceOf($objectPersister, ObjectPersisterInterface::class);

        $objectPersister->deleteById($resource->getId());
        $objectPersister->replaceOne($resource);
    }
}
