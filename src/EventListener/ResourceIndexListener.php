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
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductAttribute;
use Sylius\Component\Product\Model\ProductOption;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class ResourceIndexListener implements ResourceIndexListenerInterface
{
    public function __construct(
        private ResourceRefresherInterface $resourceRefresher,
        private array $persistersMap,
        private RepositoryInterface $attributeRepository,
        private RepositoryInterface $optionRepository
    ) {
    }

    public function updateIndex(GenericEvent $event): void
    {
        /** @var ResourceInterface|null $resource */
        $resource = $event->getSubject();

        Assert::isInstanceOf($resource, ResourceInterface::class);
        foreach ($this->persistersMap as $config) {
            $method = $config[self::GET_PARENT_METHOD_KEY] ?? null;

            if (null !== $method && method_exists($resource, $method)) {
                /** @phpstan-ignore-next-line Variable method call on mixed or other bugs */
                $resource = $resource->$method();
            }

            if ($resource instanceof $config[self::MODEL_KEY]) {
                /** @phpstan-ignore-next-line refresh method needs ResourceInterface but ECS doesn't see $resource variable in method. */
                $this->resourceRefresher->refresh($resource, $config[self::SERVICE_ID_KEY]);
            }

            if ($resource instanceof ProductInterface || $resource instanceof ProductVariantInterface) {
                if (ProductAttribute::class === $config[self::MODEL_KEY]) {
                    foreach ($this->attributeRepository->findAll() as $attribute) {
                        /** @var ResourceInterface $attribute */
                        $this->resourceRefresher->refresh($attribute, $config[self::SERVICE_ID_KEY]);
                    }
                }
                if (ProductOption::class === $config[self::MODEL_KEY]) {
                    foreach ($this->optionRepository->findAll() as $option) {
                        /** @var ResourceInterface $option */
                        $this->resourceRefresher->refresh($option, $config[self::SERVICE_ID_KEY]);
                    }
                }
            }
        }
    }
}
