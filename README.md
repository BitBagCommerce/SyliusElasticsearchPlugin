<h1 align="center">
    <a href="http://bitbag.shop" target="_blank">
        <img src="https://raw.githubusercontent.com/bitbager/BitBagCommerceAssets/master/SyliusElasticsearchPlugin.png" />
    </a>
    <br />
    <a href="https://packagist.org/packages/bitbag/elasticsearch-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/bitbag/elasticsearch-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/elasticsearch-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/bitbag/elasticsearch-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/BitBagCommerce/SyliusElasticsearchPlugin" title="Build status" target="_blank">
        <img src="https://img.shields.io/travis/BitBagCommerce/SyliusElasticsearchPlugin/master.svg" />
    </a>
    <a href="https://scrutinizer-ci.com/g/BitBagCommerce/SyliusElasticsearchPlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusElasticsearchPlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/elasticsearch-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/bitbag/elasticsearch-plugin/downloads" />
    </a>
</h1>

## Overview

Working Sylius Elasticsearch integration based on FOSElasticaBundle.

## Installation
```bash
$ composer require bitbag/elasticsearch-plugin
```
    
Add plugin dependencies to your AppKernel.php file:
```php
public function registerBundles()
{
    return array_merge(parent::registerBundles(), [
        ...
        
        new \BitBag\SyliusElasticsearchPlugin\BitBagSyliusElasticsearchPlugin(),
    ]);
}
```

Import required config in your `app/config/config.yml` file:

```yaml
# app/config/config.yml

imports:
    ...
    
    - { resource: "@BitBagSyliusElasticsearchPlugin/Resources/config/config.yml" }
```

Import routing in your `app/config/routing.yml` file:

```yaml

# app/config/routing.yml
...

bitbag_sylius_cms_plugin:
    resource: "@BitBagSyliusElasticsearchPlugin/Resources/config/routing.yml"
```

With a elasticsearch server running on port 9200 run following commands. 
```
$ bin/console fos:elastica:populate
```

*Note: if you are running it on production, add the `-e prod` flag to this command. Elastic are created with environment suffix.*

## Usage

## Testing
```bash
$ composer install
$ cd tests/Application
$ yarn install
$ yarn run gulp
$ bin/console assets:install web -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -d web -e test
$ open http://localhost:8080
$ bin/behat
$ bin/phpspec run
```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/.

## Support

Want us to help you with this plugin or any Sylius project? Write us an email on mikolaj.krol@bitbag.pl :computer:
