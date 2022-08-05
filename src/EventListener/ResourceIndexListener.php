<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresherInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class ResourceIndexListener implements ResourceIndexListenerInterface
{
    /** @var ResourceRefresherInterface */
    private $resourceRefresher;

    /** @var array */
    private $persistersMap;

    /** @var RepositoryInterface */
    private $attributeRepository;

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
                && 'Sylius\Component\Product\Model\ProductAttribute' === $config[self::MODEL_KEY]
            ) {
                foreach ($this->attributeRepository->findAll() as $attribute) {
                    $this->resourceRefresher->refresh($attribute, $config[self::SERVICE_ID_KEY]);
                }
            }
        }
    }
}
