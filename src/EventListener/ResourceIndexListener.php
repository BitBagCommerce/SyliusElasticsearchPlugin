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
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class ResourceIndexListener implements ResourceIndexListenerInterface
{
    private ResourceRefresherInterface $resourceRefresher;

    private array $persistersMap;

    private RepositoryInterface $attributeRepository;

    public function __construct(
        ResourceRefresherInterface $resourceRefresher,
        array $persistersMap,
        RepositoryInterface $attributeRepository
    ) {
        $this->resourceRefresher = $resourceRefresher;
        $this->persistersMap = $persistersMap;
        $this->attributeRepository = $attributeRepository;
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

            if ($resource instanceof $config[self::MODEL_KEY]) {
                $this->resourceRefresher->refresh($resource, $config[self::SERVICE_ID_KEY]);
            }

            if ($resource instanceof Product
                && ProductAttribute::class === $config[self::MODEL_KEY]
            ) {
                foreach ($this->attributeRepository->findAll() as $attribute) {
                    $this->resourceRefresher->refresh($attribute, $config[self::SERVICE_ID_KEY]);
                }
            }
        }
    }
}
