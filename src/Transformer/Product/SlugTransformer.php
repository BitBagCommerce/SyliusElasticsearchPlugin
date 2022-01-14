<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\Transformer\Product;

use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\Routing\RouterInterface;

final class SlugTransformer implements TransformerInterface
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function transform(ProductInterface $product): ?string
    {
        if (null === $product->getSlug()) {
            return null;
        }

        return $this->router->generate('sylius_shop_product_show', ['slug' => $product->getSlug()]);
    }
}
