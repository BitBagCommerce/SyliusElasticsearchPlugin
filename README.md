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

Working [Sylius](https://sylius.com/) [Elasticsearch](https://www.elastic.co/products/elasticsearch) integration based on [FOSElasticaBundle](https://github.com/FriendsOfSymfony/FOSElasticaBundle). The main goal of this plugin is to support filtering products by 
options, attributes, taxons, channels and name in the front product list page. It totally replaces the default Sylius `sylius_shop_product_index`
route.

What is more, the plugin has a nice Sylius-oriented architecture that allows mapping resources to the Elastic document easier. It is flexible as well,
so that you can customize the existing features for your specific business needs.   

## Support

You can order our support on [this page](https://bitbag.shop/products/sylius-elasticsearch).

We work on amazing eCommerce projects on top of Sylius and Pimcore. Need some help or additional resources for a project?
Write us an email on mikolaj.krol@bitbag.pl or visit [our website](https://bitbag.shop/)! :rocket:

## Demo

We created a demo app with some useful use-cases of the plugin! Visit [demo.bitbag.shop](https://demo.bitbag.shop/en_US/products-list/t-shirts) to take a look at it. 
The admin can be accessed under [demo.bitbag.shop/admin](https://demo.bitbag.shop/admin) link and `sylius: sylius` credentials.

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
        
        new \FOS\ElasticaBundle\FOSElasticaBundle(),
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

Import routing **on top** of your `app/config/routing.yml` file:

```yaml

# app/config/routing.yml

bitbag_sylius_elasticsearch_plugin:
    resource: "@BitBagSyliusElasticsearchPlugin/Resources/config/routing.yml"
    
redirect_sylius_shop_product_index:
    path: /{_locale}/taxons/{slug}
    controller: Symfony\Bundle\FrameworkBundle\Controller\RedirectController::redirectAction
    defaults:
        route: bitbag_sylius_elasticsearch_plugin_shop_list_products
        permanent: true
    requirements:
        slug: .+
```

With a elasticsearch server running, execute following command:
```
$ bin/console fos:elastica:populate
```

**Note:** If you are running it on production, add the `-e prod` flag to this command. Elastic are created with environment suffix.

## Usage

### Rendering the shop products list

When you go now to the `/{_locale}/products/taxon/{slug}` page, you should see a totally new set of filters. You should see something like this:

<div align="center">
    <img src="https://raw.githubusercontent.com/bitbager/BitBagCommerceAssets/master/BitBagElasticesearchProductIndex.jpg" />
</div>

You might also want to refer the horizontal menu to a new product list page. Follow below instructions to do so:

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

### Reindexing

By default, current indexes listen on all Doctrine events. You can override this setting for each index by overriding index definition in your `config.yml` file:

```yml
fos_elastica:
    indexes:
        bitbag_attribute_taxons:
            types:
                default:
                    persistence:
                        listener:
                            insert: true
                            update: false
                            delete: true
```

Indexes with `bitbag_shop_product`, `bitbag_attribute_taxons` and `bitbag_option_taxons` keys are available so far.

## Customization

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

```bash
bitbag.sylius_elasticsearch_plugin.context.product_attributes                                BitBag\SyliusElasticsearchPlugin\Context\ProductAttributesContext
bitbag.sylius_elasticsearch_plugin.context.product_options                                   BitBag\SyliusElasticsearchPlugin\Context\ProductOptionsContext
bitbag.sylius_elasticsearch_plugin.context.taxon                                             BitBag\SyliusElasticsearchPlugin\Context\TaxonContext
bitbag.sylius_elasticsearch_plugin.string_formatter                                          BitBag\SyliusElasticsearchPlugin\Formatter\StringFormatter
bitbag.sylius_elasticsearch_plugin.twig.extension.unset_array_elements                       BitBag\SyliusElasticsearchPlugin\Twig\Extension\UnsetArrayElementsExtension
bitbag_sylius_elasticsearch_plugin.controller.action.shop.list_products                      BitBag\SyliusElasticsearchPlugin\Controller\Action\Shop\ListProductsAction
bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.pagination                BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\PaginationDataHandler
bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.shop_product_list         BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductListDataHandler
bitbag_sylius_elasticsearch_plugin.controller.request_data_handler.shop_products_sort        BitBag\SyliusElasticsearchPlugin\Controller\RequestDataHandler\ShopProductsSortDataHandler
bitbag_sylius_elasticsearch_plugin.finder.product_attributes                                 BitBag\SyliusElasticsearchPlugin\Finder\ProductAttributesFinder
bitbag_sylius_elasticsearch_plugin.finder.product_options                                    BitBag\SyliusElasticsearchPlugin\Finder\ProductOptionsFinder
bitbag_sylius_elasticsearch_plugin.finder.shop_products                                      BitBag\SyliusElasticsearchPlugin\Finder\ShopProductsFinder
bitbag_sylius_elasticsearch_plugin.form.type.choice_mapper.product_attributes                BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\ProductAttributesMapper
bitbag_sylius_elasticsearch_plugin.form.type.choice_mapper.product_options                   BitBag\SyliusElasticsearchPlugin\Form\Type\ChoiceMapper\ProductOptionsMapper
bitbag_sylius_elasticsearch_plugin.form.type.name_filter                                     BitBag\SyliusElasticsearchPlugin\Form\Type\NameFilterType
bitbag_sylius_elasticsearch_plugin.form.type.price_filter                                    BitBag\SyliusElasticsearchPlugin\Form\Type\PriceFilterType
bitbag_sylius_elasticsearch_plugin.form.type.product_attributes_filter                       BitBag\SyliusElasticsearchPlugin\Form\Type\ProductAttributesFilterType
bitbag_sylius_elasticsearch_plugin.form.type.product_options_filter                          BitBag\SyliusElasticsearchPlugin\Form\Type\ProductOptionsFilterType
bitbag_sylius_elasticsearch_plugin.form.type.shop_products_filter                            BitBag\SyliusElasticsearchPlugin\Form\Type\ShopProductsFilterType
bitbag_sylius_elasticsearch_plugin.property_builder.attribute                                BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AttributeBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.attribute_taxons                         BitBag\SyliusElasticsearchPlugin\PropertyBuilder\AttributeTaxonsBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.channel_pricing                          BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ChannelPricingBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.channels                                 BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ChannelsBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.option                                   BitBag\SyliusElasticsearchPlugin\PropertyBuilder\OptionBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.option_taxons                            BitBag\SyliusElasticsearchPlugin\PropertyBuilder\OptionTaxonsBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.product_created_at                       BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductCreatedAtPropertyBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.product_name                             BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductNameBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.product_taxons                           BitBag\SyliusElasticsearchPlugin\PropertyBuilder\ProductTaxonsBuilder
bitbag_sylius_elasticsearch_plugin.property_builder.sold_units                               BitBag\SyliusElasticsearchPlugin\PropertyBuilder\SoldUnitsPropertyBuilder
bitbag_sylius_elasticsearch_plugin.property_name_resolver.attribute                          BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolver
bitbag_sylius_elasticsearch_plugin.property_name_resolver.channel_pricing                    BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolver
bitbag_sylius_elasticsearch_plugin.property_name_resolver.name                               BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolver
bitbag_sylius_elasticsearch_plugin.property_name_resolver.option                             BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\ConcatedNameResolver
bitbag_sylius_elasticsearch_plugin.property_name_resolver.price                              BitBag\SyliusElasticsearchPlugin\PropertyNameResolver\PriceNameResolver
bitbag_sylius_elasticsearch_plugin.query_builder.contains_name                               BitBag\SyliusElasticsearchPlugin\QueryBuilder\ContainsNameQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.has_attribute_taxon                         BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasTaxonQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.has_attributes                              BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasAttributesQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.has_channel                                 BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasChannelQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.has_option_taxon                            BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasTaxonQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.has_options                                 BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasOptionsQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.has_price_between                           BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasPriceBetweenQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.has_product_taxon                           BitBag\SyliusElasticsearchPlugin\QueryBuilder\HasTaxonQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.is_enabled                                  BitBag\SyliusElasticsearchPlugin\QueryBuilder\IsEnabledQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.product_attributes_by_taxon                 BitBag\SyliusElasticsearchPlugin\QueryBuilder\ProductAttributesByTaxonQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.product_options_by_taxon                    BitBag\SyliusElasticsearchPlugin\QueryBuilder\ProductOptionsByTaxonQueryBuilder
bitbag_sylius_elasticsearch_plugin.query_builder.shop_products                               BitBag\SyliusElasticsearchPlugin\QueryBuilder\ShopProductsQueryBuilder
```

### Parameters you can override in your parameters.yml(.dist) file

```yml
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
$ elasticsearch 
$ bin/console fos:elastica:populate -e test
$ bin/console server:run 127.0.0.1:8080 -d web -e test
$ open http://localhost:8080
$ bin/behat
$ bin/phpspec run
```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/.
