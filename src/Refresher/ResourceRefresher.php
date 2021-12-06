<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

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
