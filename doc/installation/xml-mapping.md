# XML-mapping

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
                        type: xml
                        dir: '%kernel.project_dir%/src/Resources/config/doctrine'
```

Update entity with traits:

- `ProductVariant` entity:

`src/Entity/ProductVariant.php`
```php
<?php

declare(strict_types=1);

namespace App\Entity;

use BitBag\SyliusElasticsearchPlugin\Model\ProductVariantInterface as BitBagElasticsearchPluginVariant;
use BitBag\SyliusElasticsearchPlugin\Model\ProductVariantTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ProductVariantInterface as BaseProductVariantInterface;
use Sylius\Component\Core\Model\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant implements BaseProductVariantInterface, BitBagElasticsearchPluginVariant
{
    use ProductVariantTrait;
    
    // ...
}
```

Define new Entity mapping inside `src/Resources/config/doctrine` directory.

- `ProductVariant` entity:

`src/Resources/config/doctrine/ProductVariant.orm.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>

<doctrine-mapping xmlns="http://doctrine-project.org/schemas/orm/doctrine-mapping"
                  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                  xsi:schemaLocation="http://doctrine-project.org/schemas/orm/doctrine-mapping
                            http://doctrine-project.org/schemas/orm/doctrine-mapping.xsd">
    <entity name="App\Entity\ProductVariant" table="sylius_product_variant">
    </entity>
</doctrine-mapping>
```

Override `config/packages/_sylius.yaml` configuration:
```yaml
# config/_sylius.yaml

sylius_product:
    resources:
        product_variant:
            classes:
                model: App\Entity\ProductVariant
```
