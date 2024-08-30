# Installation

## Overview:
GENERAL
- [Requirements](#requirements)
- [Composer](#composer)
- [Basic configuration](#basic-configuration)
--- 
BACKEND
- [Entities](#entities)
---
FRONTEND
- [Webpack](#webpack)
---
ADDITIONAL
- [Additional configuration](#additional-configuration)
- [Known Issues](#known-issues)
---

## Requirements:
**Note:** This Plugin supports ElasticSearch 7.0 and above. If you're looking for ElasticSearch Plugin for older versions check SyliusElasticSearchPlugin in version 1.x.

We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

| Package       | Version         |
|---------------|-----------------|
| PHP           | \>=8.1          |
| sylius/sylius | 1.12.x - 1.13.x |
| MySQL         | \>= 5.7         |
| NodeJS        | \>= 18.x        |
| ElasticSearch | \>= 7.x         |

## Composer:
```bash
composer require bitbag/elasticsearch-plugin --no-scripts
```

## Basic configuration:
Add plugin dependencies to your `config/bundles.php` file:

```php
# config/bundles.php

return [
    ...
    FOS\ElasticaBundle\FOSElasticaBundle::class => ['all' => true],
    BitBag\SyliusElasticsearchPlugin\BitBagSyliusElasticsearchPlugin::class => ['all' => true],
];
```

Import required config in your `config/packages/_sylius.yaml` file:

```yaml
# config/packages/_sylius.yaml

imports:
    ...
    - { resource: "@BitBagSyliusElasticsearchPlugin/Resources/config/config.yml" }
```

Import routing on top of your `config/routes.yaml` file:

```yaml
# config/routes.yaml

bitbag_sylius_elasticsearch_plugin:
    resource: "@BitBagSyliusElasticsearchPlugin/Resources/config/routing.yml"
```

...and set up the redirection from the default Sylius shop products index page on top of your config/routes/sylius_shop.yaml file.
```yaml
# config/routes/sylius_shop.yaml

redirect_sylius_shop_product_index:
    path: /{_locale}/taxons/{slug}
    controller: Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction
    defaults:
        route: bitbag_sylius_elasticsearch_plugin_shop_list_products
        permanent: true
    requirements:
        _locale: ^[a-z]{2}(?:_[A-Z]{2})?$
        slug: .+
```

Remove the default ElasticSearch index (`app`) defined by `FOSElasticaBundle` in `config/packages/fos_elastica.yaml`:
```yaml
# config/packages/fos_elastica.yaml

fos_elastica:
    clients:
        default: { url: '%env(ELASTICSEARCH_URL)%' }
    indexes:
        app: ~
```
should become:

```yaml
fos_elastica:
    clients:
        default: { url: '%env(ELASTICSEARCH_URL)%' }
```

## Entities
Use `BitBag\SyliusElasticsearchPlugin\Model\ProductVariantTrait` and `BitBag\SyliusElasticsearchPlugin\Model\ProductVariantInterface` in an overridden ProductVariant entity class.
The configuration, depending on the mapping used, may vary.

### Attribute mapping
- [Attribute mapping configuration](installation/attribute-mapping.md)
### XML mapping
- [XML mapping configuration](installation/xml-mapping.md)

### Update installed assets with the following command:
```bash
bin/console assets:install
```

### Clear application cache by using command:
```bash
bin/console cache:clear
```

### Finally, with an elasticsearch server running, execute following command:
```bash
bin/console fos:elastica:populate
```

**Note:** If you are running it on production, add the `-e prod` flag to this command. Elastic are created with environment suffix.

## Webpack
### Webpack.config.js

Please setup your `webpack.config.js` file to require the plugin's webpack configuration. To do so, please put the line below somewhere on top of your webpack.config.js file:
```js
const [ bitbagElasticSearchShop ] = require('./vendor/bitbag/elasticsearch-plugin/webpack.config.js')
```
As next step, please add the imported consts into final module exports:
```js
module.exports = [..., bitbagElasticSearchShop];
```

### Assets
Add the asset configuration into `config/packages/assets.yaml`:
```yaml
framework:
    assets:
        packages:
            ...
            elasticsearch_shop:
                json_manifest_path: '%kernel.project_dir%/public/build/bitbag/elasticsearch/shop/manifest.json'
```

### Webpack Encore
Add the webpack configuration into `config/packages/webpack_encore.yaml`:

```yaml
webpack_encore:
    output_path: '%kernel.project_dir%/public/build/default'
    builds:
        ...
        elasticsearch_shop: '%kernel.project_dir%/public/build/bitbag/elasticsearch/shop'
```

### Encore functions
Add encore functions to your templates:

SyliusShopBundle:
```php
{# @SyliusShopBundle/_scripts.html.twig #}
{{ encore_entry_script_tags('bitbag-elasticsearch-shop', null, 'elasticsearch_shop') }}

{# @SyliusShopBundle/_styles.html.twig #}
{{ encore_entry_link_tags('bitbag-elasticsearch-shop', null, 'elasticsearch_shop') }}
```

### Run commands
```bash
yarn install
yarn encore dev # or prod, depends on your environment
```

## Additional configuration
Elasticsearch's port settings can be found in the `.env` file.

```dotenv
###> friendsofsymfony/elastica-bundle ###
ELASTICSEARCH_URL=http://localhost:9200/
###< friendsofsymfony/elastica-bundle ###
```

## Known issues
### Translations not displaying correctly
For incorrectly displayed translations, execute the command:
```bash
bin/console cache:clear
```
