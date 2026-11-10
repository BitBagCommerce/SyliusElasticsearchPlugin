<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\SyliusElasticsearchPlugin\EventListener\ResourceIndexListener;
use BitBag\SyliusElasticsearchPlugin\EventListener\ResourceIndexListenerInterface;
use BitBag\SyliusElasticsearchPlugin\Refresher\ResourceRefresherInterface;
use FOS\ElasticaBundle\Persister\ObjectPersisterInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Product\Model\ProductAttribute;
use Sylius\Component\Product\Model\ProductAttributeInterface;
use Sylius\Component\Product\Model\ProductOption;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

final class ResourceIndexListenerSpec extends ObjectBehavior
{
    function let(
        ResourceRefresherInterface $resourceRefresher,
        RepositoryInterface $attributeRepository,
        RepositoryInterface $optionRepository,
        ObjectPersisterInterface $attributePersister,
        ObjectPersisterInterface $optionPersister,
        ObjectPersisterInterface $productPersister
    ): void {
        // Application models, as mapped in Sylius Standard (App\Entity\Product\ProductAttribute etc.)
        $this->beConstructedWith(
            $resourceRefresher,
            $this->persistersMap(
                (new class() extends ProductAttribute {})::class,
                (new class() extends ProductOption {})::class,
                $attributePersister,
                $optionPersister,
                $productPersister
            ),
            $attributeRepository,
            $optionRepository
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ResourceIndexListener::class);
    }

    function it_implements_resource_index_listener_interface(): void
    {
        $this->shouldHaveType(ResourceIndexListenerInterface::class);
    }

    function it_refreshes_all_attributes_and_options_when_a_product_is_saved_with_application_models(
        ResourceRefresherInterface $resourceRefresher,
        RepositoryInterface $attributeRepository,
        RepositoryInterface $optionRepository,
        ObjectPersisterInterface $attributePersister,
        ObjectPersisterInterface $optionPersister,
        ObjectPersisterInterface $productPersister,
        ProductInterface $product,
        ProductAttributeInterface $attribute,
        ProductOptionInterface $option
    ): void {
        $attributeRepository->findAll()->willReturn([$attribute]);
        $optionRepository->findAll()->willReturn([$option]);

        $resourceRefresher->refresh($attribute, $attributePersister)->shouldBeCalled();
        $resourceRefresher->refresh($option, $optionPersister)->shouldBeCalled();
        $resourceRefresher->refresh($product, $productPersister)->shouldBeCalled();

        $this->updateIndex(new GenericEvent($product->getWrappedObject()));
    }

    function it_refreshes_all_attributes_and_options_when_a_variant_is_saved_with_application_models(
        ResourceRefresherInterface $resourceRefresher,
        RepositoryInterface $attributeRepository,
        RepositoryInterface $optionRepository,
        ObjectPersisterInterface $attributePersister,
        ObjectPersisterInterface $optionPersister,
        ObjectPersisterInterface $productPersister,
        ProductVariantInterface $variant,
        ProductInterface $product,
        ProductAttributeInterface $attribute,
        ProductOptionInterface $option
    ): void {
        $variant->getProduct()->willReturn($product);
        $attributeRepository->findAll()->willReturn([$attribute]);
        $optionRepository->findAll()->willReturn([$option]);

        $resourceRefresher->refresh($attribute, $attributePersister)->shouldBeCalled();
        $resourceRefresher->refresh($option, $optionPersister)->shouldBeCalled();
        $resourceRefresher->refresh($product, $productPersister)->shouldBeCalled();

        $this->updateIndex(new GenericEvent($variant->getWrappedObject()));
    }

    function it_refreshes_all_attributes_and_options_when_a_product_is_saved_with_sylius_models(
        ResourceRefresherInterface $resourceRefresher,
        RepositoryInterface $attributeRepository,
        RepositoryInterface $optionRepository,
        ObjectPersisterInterface $attributePersister,
        ObjectPersisterInterface $optionPersister,
        ObjectPersisterInterface $productPersister,
        ProductInterface $product,
        ProductAttributeInterface $attribute,
        ProductOptionInterface $option
    ): void {
        $this->beConstructedWith(
            $resourceRefresher,
            $this->persistersMap(
                ProductAttribute::class,
                ProductOption::class,
                $attributePersister,
                $optionPersister,
                $productPersister
            ),
            $attributeRepository,
            $optionRepository
        );

        $attributeRepository->findAll()->willReturn([$attribute]);
        $optionRepository->findAll()->willReturn([$option]);

        $resourceRefresher->refresh($attribute, $attributePersister)->shouldBeCalled();
        $resourceRefresher->refresh($option, $optionPersister)->shouldBeCalled();
        $resourceRefresher->refresh($product, $productPersister)->shouldBeCalled();

        $this->updateIndex(new GenericEvent($product->getWrappedObject()));
    }

    function it_does_not_refresh_all_attributes_and_options_when_an_attribute_is_saved(
        RepositoryInterface $attributeRepository,
        RepositoryInterface $optionRepository,
        ProductAttributeInterface $attribute
    ): void {
        $attributeRepository->findAll()->shouldNotBeCalled();
        $optionRepository->findAll()->shouldNotBeCalled();

        $this->updateIndex(new GenericEvent($attribute->getWrappedObject()));
    }

    private function persistersMap(
        string $attributeModel,
        string $optionModel,
        $attributePersister,
        $optionPersister,
        $productPersister
    ): array {
        return [
            [
                ResourceIndexListenerInterface::MODEL_KEY => $attributeModel,
                ResourceIndexListenerInterface::SERVICE_ID_KEY => $attributePersister,
            ],
            [
                ResourceIndexListenerInterface::MODEL_KEY => $optionModel,
                ResourceIndexListenerInterface::SERVICE_ID_KEY => $optionPersister,
            ],
            [
                ResourceIndexListenerInterface::GET_PARENT_METHOD_KEY => 'getProduct',
                ResourceIndexListenerInterface::MODEL_KEY => ProductInterface::class,
                ResourceIndexListenerInterface::SERVICE_ID_KEY => $productPersister,
            ],
        ];
    }
}
