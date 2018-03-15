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

Working Sylius Elasticsearch integration based on FOSElasticaBundle. The main goal of this plugin is to support filtering products by 
options, attributes, taxons, channels and name in the front product list page. It totally replaces the default Sylius `sylius_shop_product_index`
route.

What is more, the plugin has a nice Sylius-oriented architecture that allows mapping resources to the Elastic document easier. It is flexible as well,
so that you can customize the existing features for your specific business needs.   

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

### Rendering the shop products list

When you go now to the `/{_locale}/products/taxon/{slug}` page, you should see a totally new set of filters. You might also want to refer 
the horizontal menu to a new product list page. Follow below instructions to do so:

1. If you haven't done it yet, create a `_horizontalMenu.html.twig` file in `app/Resources/SyliusShopBundle/views/Taxon` directory.
2. Replace `sylius_shop_product_index` with `bitbag_sylius_elasticsearch_plugin_shop_list_products`.
3. Clean your cache with `bin/console cache:clear` command.
4. :tada:

### Excluding options and attributes in the filter menu

You might not want to show some specific options or attributes in the menu. You can set specific parameters for that:

```yml
parameters:
    bitbag_es_excluded_filter_options: []
    bitbag_es_excluded_filter_attributes: ['book_isbn', 'book_pages']
```

By default all options and attributes are indexed. After you change these parameters, remember to run `bin/console fo:el:po` command again
(a shortcut for `fos:elastica:populate`).


## Customization

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

```bash
bitbag_es_excluded_filter_attributes                                      ["book_isbn","book_pages"]                                                                                                  
bitbag_es_excluded_filter_options                                         []                                                                                                                          
bitbag_es_host                                                            localhost                                                                                                                   
bitbag_es_port                                                            9200                                                                                                                        
bitbag_es_shop_attribute_property_prefix                                  attribute                                                                                                                   
bitbag_es_shop_attribute_taxons_property                                  attribute_taxons                                                                                                            
bitbag_es_shop_channels_property                                          channels                                                                                                                    
bitbag_es_shop_enabled_property                                           enabled                                                                                                                     
bitbag_es_shop_name_property_prefix                                       name                                                                                                                        
bitbag_es_shop_option_property_prefix                                     option                                                                                                                      
bitbag_es_shop_option_taxons_property                                     option_taxons                                                                                                               
bitbag_es_shop_product_created_at                                         product_created_at                                                                                                          
bitbag_es_shop_product_price_property_prefix                              price                                                                                                                       
bitbag_es_shop_product_sold_units                                         sold_units                                                                                                                  
bitbag_es_shop_product_taxons_property                                    product_taxons                                                                                                              
fos_elastica.default_index                                                bitbag_shop_product
```

### Parameters you can override in your parameters.yml(.dist) file

```yml
parameters:
bitbag_es_excluded_filter_attributes                                      []                                                                                                  
bitbag_es_excluded_filter_options                                         []                                                                                                                          
bitbag_es_shop_attribute_property_prefix                                  attribute                                                                                                                   
bitbag_es_shop_attribute_taxons_property                                  attribute_taxons                                                                                                            
bitbag_es_shop_channels_property                                          channels                                                                                                                    
bitbag_es_shop_enabled_property                                           enabled                                                                                                                     
bitbag_es_shop_name_property_prefix                                       name                                                                                                                        
bitbag_es_shop_option_property_prefix                                     option                                                                                                                      
bitbag_es_shop_option_taxons_property                                     option_taxons                                                                                                               
bitbag_es_shop_product_created_at                                         product_created_at                                                                                                          
bitbag_es_shop_product_price_property_prefix                              price                                                                                                                       
bitbag_es_shop_product_sold_units                                         sold_units                                                                                                                  
bitbag_es_shop_product_taxons_property                                    product_taxons                                                                                                              
fos_elastica.default_index                                                bitbag_shop_product    
```

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

We work on amazing eCommerce projects on top of Sylius, Pimcore and Vue JS. Need some help or additional resources for a project?
Write us an email on mikolaj.krol@bitbag.pl! :computer:
