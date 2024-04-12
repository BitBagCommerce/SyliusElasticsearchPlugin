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
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Product\Model\ProductAttribute;
use Sylius\Component\Product\Model\ProductOption;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class ResourceIndexListener implements ResourceIndexListenerInterface
{
    private ResourceRefresherInterface $resourceRefresher;

    private array $persistersMap;

    private RepositoryInterface $attributeRepository;

    private RepositoryInterface $productOptionRepository;

    public function __construct(
        ResourceRefresherInterface $resourceRefresher,
        array $persistersMap,
        RepositoryInterface $attributeRepository,
        RepositoryInterface $productOptionRepository
    ) {
        $this->resourceRefresher = $resourceRefresher;
        $this->persistersMap = $persistersMap;
        $this->attributeRepository = $attributeRepository;
        $this->productOptionRepository = $productOptionRepository;
    }

    public function updateIndex(GenericEvent $event): void
    {
        $resource = $event->getSubject();

        Assert::isInstanceOf($resource, ResourceInterface::class);
        foreach ($this->persistersMap as $config) {
            $method = $config[self::GET_PARENT_METHOD_KEY] ?? null;

            if (null !== $method && method_exists($resource, $method)) {
                $resource = $resource->$method();
            }

            $this->refreshResource($resource, $config);
        }
    }

    private function refreshResource(
        ResourceInterface $resource,
        array $config
    ): void {
        if ($resource instanceof $config[self::MODEL_KEY]) {
            $this->resourceRefresher->refresh(
                $resource,
                $config[self::SERVICE_ID_KEY]
            );
        }

        if (!$resource instanceof Product) {
            return;
        }

        $resourceRepository = $this->getResourceRespository($config);

        if (null === $resourceRepository) {
            return;
        }

        foreach ($resourceRepository->findAll() as $resourceItem) {
            $this->resourceRefresher->refresh(
                $resourceItem,
                $config[self::SERVICE_ID_KEY]
            );
        }
    }

    private function getResourceRespository(array $config): ?RepositoryInterface
    {
        if (ProductAttribute::class === $config[self::MODEL_KEY]) {
            return $this->attributeRepository;
        }

        if (ProductOption::class === $config[self::MODEL_KEY]) {
            return $this->productOptionRepository;
        }

        return null;
    }
}
