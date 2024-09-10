# Attribute-mapping

Check the mapping settings in `config/packages/doctrine.yaml`.
```yaml
doctrine:
    ...
    orm:
        entity_managers:
            default:
                ...
                mappings:
                    App:
                        ...
                        type: attribute
```

Update entity with traits:
```php
<?php

declare(strict_types=1);

namespace App\Entity\Product;

use BitBag\SyliusElasticsearchPlugin\Model\ProductVariantInterface as BitBagElasticsearchPluginVariant;
use BitBag\SyliusElasticsearchPlugin\Model\ProductVariantTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_product_variant')]
class ProductVariant extends BaseProductVariant implements BitBagElasticsearchPluginVariant
{
    use ProductVariantTrait;
    
    // ...
}
```
