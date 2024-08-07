# [![](https://bitbag.io/wp-content/uploads/2021/01/elasticsearch.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)

# BitBag SyliusElasticsearchPlugin

----

[![](https://img.shields.io/packagist/l/bitbag/elasticsearch-plugin.svg) ](https://packagist.org/packages/bitbag/elasticsearch-plugin "License") [ ![](https://img.shields.io/packagist/v/bitbag/elasticsearch-plugin.svg) ](https://packagist.org/packages/bitbag/elasticsearch-plugin "Version") [ ![](https://img.shields.io/github/actions/workflow/status/BitBagCommerce/SyliusElasticsearchPlugin/build.yml) ](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/actions "Build status") [ ![](https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusElasticsearchPlugin.svg) ](https://scrutinizer-ci.com/g/BitBagCommerce/SyliusElasticsearchPlugin/ "Scrutinizer") [![](https://poser.pugx.org/bitbag/elasticsearch-plugin/downloads)](https://packagist.org/packages/bitbag/elasticsearch-plugin "Total Downloads") [![Slack](https://img.shields.io/badge/community%20chat-slack-FF1493.svg)](http://sylius-devs.slack.com) [![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)

<p>
 <img align="left" src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
</p> 

At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us to work  together, feel free to reach out. You will find out more about our professional services, technologies, and contact details at [https://bitbag.io/](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch).

Like what we do? Want to join us? Check out our job listings on our [career page](https://bitbag.io/career/?utm_source=github&utm_medium=referral&utm_campaign=career). Not familiar with Symfony & Sylius yet, but still want to start with us? Join our [academy](https://bitbag.io/pl/akademia?utm_source=github&utm_medium=url&utm_campaign=akademia)!

## Table of Content

***

* [Overview](#overview)
* [Support](#we-are-here-to-help)
* [Installation](#installation)
   * [Usage](#usage)
   * [Customization](#customization)
   * [Testing](#testing)
* [About us](#about-us)
   * [Community](#community)
* [Demo](#demo-sylius-shop)
* [License](#license)
* [Contact](#contact)

# Overview

----
Working [Sylius](https://sylius.com/) [Elasticsearch](https://www.elastic.co/products/elasticsearch) integration based on [FOSElasticaBundle](https://github.com/FriendsOfSymfony/FOSElasticaBundle). The main goal of this plugin is to support filtering products by options, attributes, taxons, channels and name in the front product list page. It totally replaces the default Sylius `sylius_shop_product_index` route.

If you are curious about the details of this plugin, read [this Medium blog post](https://medium.com/@BitBag/using-sylius-and-elasticsearch-for-a-products-filter-6dc9f0ce929) and watch the video below.

[![](https://img.youtube.com/vi/DUswiGQePLE/0.jpg)](https://www.youtube.com/watch?v=DUswiGQePLE)

What is more, the plugin has a nice Sylius-oriented architecture that allows mapping resources to the Elastic document easier. It is flexible as well, so that you can customize the existing features for your specific business needs.


## We are here to help
This **open-source plugin was developed to help the Sylius community**. If you have any additional questions, would like help with installing or configuring the plugin, or need any assistance with your Sylius project - let us know!

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)


# Requirements

----

This plugin requires elasticsearch server running. You can install it by following the instructions on the [official website](https://www.elastic.co/guide/en/elasticsearch/reference/current/install-elasticsearch.html).
In plugin repository there is Docker Compose file that can be used to run Elasticsearch server.

# Installation

----

We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.

*Note*: This Plugin supports ElasticSearch 7.0 and above. If you're looking for ElasticSearch Plugin for older versions check SyliusElasticSearchPlugin in version `1.x`.

```bash
$ composer require bitbag/elasticsearch-plugin --no-scripts
```


Add plugin dependencies to your `config/bundles.php` file:
```php
return [
    ...

    FOS\ElasticaBundle\FOSElasticaBundle::class => ['all' => true],
    BitBag\SyliusElasticsearchPlugin\BitBagSyliusElasticsearchPlugin::class => ['all' => true],
];
```

Use trait `BitBag\SyliusElasticsearchPlugin\Model\ProductVariantTrait` in an overridden ProductVariant entity class. [see how to overwrite a sylius model](https://docs.sylius.com/en/1.9/customization/model.html)

also Use `BitBag\SyliusElasticsearchPlugin\Model\ProductVariantInterface` interface in ProductVariant entity class.
The final effect should look like the following:

```
use BitBag\SyliusElasticsearchPlugin\Model\ProductVariantInterface as BitBagElasticsearchPluginVariant;
use BitBag\SyliusElasticsearchPlugin\Model\ProductVariantTrait;
use Sylius\Component\Core\Model\ProductVariantInterface as BaseProductVariantInterface;

class ProductVariant extends BaseProductVariant implements BaseProductVariantInterface, BitBagElasticsearchPluginVariant
{
    use ProductVariantTrait;
    
    ...
}
```

Import required config in your `config/packages/_sylius.yaml` file:
```yaml
# config/packages/_sylius.yaml

imports:
    ...
    
    - { resource: "@BitBagSyliusElasticsearchPlugin/Resources/config/config.yml" }
```

Import routing **on top** of your `config/routes.yaml` file:
```yaml
# config/routes.yaml

bitbag_sylius_elasticsearch_plugin:
    resource: "@BitBagSyliusElasticsearchPlugin/Resources/config/routing.yml"
```

...and set up the redirection from the default Sylius shop products index page on top of your `config/routes/sylius_shop.yaml`
file.
```
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
...and update installed assets with the following command:
```
$ bin/console assets:install
```
...and remove the default ElasticSearch index (`app`) defined by `FOSElasticaBundle` in `config/packages/fos_elastica.yaml`:
```

fos_elastica:
    clients:
        default: { url: '%env(ELASTICSEARCH_URL)%' }
    indexes:
        app: ~
```
should become:
```

fos_elastica:
    clients:
        default: { url: '%env(ELASTICSEARCH_URL)%' }
```
In the end, with an elasticsearch server running, execute following commands:
```
$ bin/console cache:clear
$ bin/console fos:elastica:populate
```

**Note:** If you are running it on production, add the `-e prod` flag to this command. Elastic are created with environment suffix.

### Configuring Webpack

1. Import plugin's `webpack.config.js` file

```js
// webpack.config.js
const [ bitbagElasticSearchShop ] = require('./vendor/bitbag/elasticsearch-plugin/webpack.config.js')
...

module.exports = [..., bitbagElasticSearchShop];
```

2. Add new packages in `./config/packages/assets.yaml`

```yml
# config/packages/assets.yaml

framework:
    assets:
        packages:
            # ...
            elasticsearch_shop:
                json_manifest_path: '%kernel.project_dir%/public/build/bitbag/elasticsearch/shop/manifest.json'
```

3. Add new build paths in `./config/packages/webpack_encore.yml`

```yml
# config/packages/webpack_encore.yml

webpack_encore:
    builds:
        # ...
        elasticsearch_shop: '%kernel.project_dir%/public/build/bitbag/elasticsearch/shop'
```

4. Add encore functions to your templates

```twig
{# @SyliusShopBundle/_scripts.html.twig #}
{{ encore_entry_script_tags('bitbag-elasticsearch-shop', null, 'elasticsearch_shop') }}

{# @SyliusShopBundle/_styles.html.twig #}
{{ encore_entry_link_tags('bitbag-elasticsearch-shop', null, 'elasticsearch_shop') }}
```

5. Run `yarn encore dev` or `yarn encore production`

## Functionalities 

All main functionalities of the plugin are described [here.](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/doc/functionalities.md)

## Usage

### Scope of the search

This plugin offers a site-wide search feature and taxon search feature. It is easily extendable to add more search scopes. For example in Marketplace suite you can create Vendor specific search scope.

### Searching site-wide products

There is searchbar in the header of the shop. 

<div align="center">
    <img src="doc/es_browser.png" />
</div>

You can easily modify it by overriding the `@BitBagSyliusElasticsearchPlugin/Shop/Menu/_searchForm.html.twig` template or disable it by setting:
```yml
sylius_ui:
  events:
    sylius.shop.layout.header.content:
      blocks:
        bitbag_es_search_form:
          enabled: false
```

### Searching taxon products

When you go now to the `/{_locale}/products-list/{taxon-slug}` page, you should see a totally new set of filters. You should see something like this:

<div align="center">
    <img src="doc/es_results.png" />
</div>

You might also want to refer the horizontal menu to a new product list page. Follow below instructions to do so:

1. If you haven't done it yet, create two files:
   * `_horizontalMenu.html.twig` in `templates/bundles/SyliusShopBundle/Taxon` directory
   * `_breadcrumb.html.twig` in `templates/bundles/SyliusShopBundle/Product/Show` directory
2. Paste into those files content of respectively `vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Resources/views/Taxon/_horizontalMenu.html.twig` and `vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Resources/views/Product/Show/_breadcrumb.html.twig` files, replacing `sylius_shop_product_index` with `bitbag_sylius_elasticsearch_plugin_shop_list_products` in both of them.
3. Clean your cache with `bin/console cache:clear` command.
4. :tada:

If you're using vertical menu - follow steps above with `_verticalMenu.html.twig` file instead. It's in the same directory as the `_horizontalMenu.html.twig` file.

**Be aware! Elasticsearch does not handle dashes well. This plugin depends on the code field in Sylius resources. Please use underscores instead of dashes in your code fields.**

### Excluding options and attributes in the filter menu

You might not want to show some specific options or attributes in the menu. You can set specific parameters for that:
```yml
parameters:
    bitbag_es_excluded_facet_attributes: ['jeans_material']
    bitbag_es_excluded_facet_options: ['t_shirt_size']
```

By default, all options and attributes filters are shown.

It is also possible to disable options and attribute filters autodiscovery by setting the following parameters:
```yml
parameters:
    bitbag_es_facets_auto_discover: false
```

Then you have to manually register your filters:

Available filters:
* [`TaxonFacet`](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/src/Facet/TaxonFacet.php) which allows to filter your search results by taxons using the ElasticSearch [`Terms`](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html) aggregation.
* [`AttributeFacet`](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/src/Facet/AttributeFacet.php) which allows to filter your search results by product attributes values using the ElasticSearch [`Terms`](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html) aggregation.
* [`OptionFacet`](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/src/Facet/OptionFacet.php) which is the same as `AttributeFacet` but for product options.
* [`PriceFacet`](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/src/Facet/PriceFacet.php) which allows to filter search results by price range the ElasticSearch [`Histogram`](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-histogram-aggregation.html) aggregation.

Example of manual registration of filters:
```yml
services:
    bitbag_sylius_elasticsearch_plugin.facet.attribute.t_shirt_brand:
      class: BitBag\SyliusElasticsearchPlugin\Facet\AttributeFacet
      arguments:
        - '@bitbag_sylius_elasticsearch_plugin.property_name_resolver.attribute'
        - '@=service("sylius.repository.product_attribute").findOneBy({"code": "t_shirt_brand"})'
        - '@sylius.context.locale'

    bitbag_sylius_elasticsearch_plugin.facet.registry:
      class: BitBag\SyliusElasticsearchPlugin\Facet\Registry
      calls:
        -   method: addFacet
            arguments:
              - t_shirt_brand
              - '@bitbag_sylius_elasticsearch_plugin.facet.attribute.t_shirt_brand'
        - method: addFacet
          arguments:
            - price
            - '@bitbag_sylius_elasticsearch_plugin.facet.price'
        - method: addFacet
          arguments:
            - taxon
            - '@bitbag_sylius_elasticsearch_plugin.facet.taxon'
```

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
$ bin/console debug:container | grep bitbag_sylius_elasticsearch_plugin
```

### Parameters you can override in your parameters.yml(.dist) file
```bash
$ bin/console debug:container --parameters | grep bitbag
```

## Testing
```bash
$ composer install
$ cd tests/Application
$ APP_ENV=test bin/console doctrine:database:create
$ APP_ENV=test bin/console doctrine:schema:create
// run elasticsearch
$ APP_ENV=test bin/console sylius:fixtures:load
$ APP_ENV=test bin/console fos:elastica:populate
$ APP_ENV=test symfony server:run 127.0.0.1:8080 -d
$ APP_ENV=test bin/console assets:install
$ open http://localhost:8080
$ vendor/bin/behat
$ vendor/bin/phpspec run
```

# About us

---

BitBag is a company of people who **love what they do** and do it right. We fulfill the eCommerce technology stack with **Sylius**, Shopware, Akeneo, and Pimcore for PIM, eZ Platform for CMS, and VueStorefront for PWA. Our goal is to provide real digital transformation with an agile solution that scales with the **clients’ needs**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.</br>
We are advisers in the first place. We start each project with a diagnosis of problems, and an analysis of the needs and **goals** that the client wants to achieve.</br>
We build **unforgettable**, consistent digital customer journeys on top of the **best technologies**. Based on a detailed analysis of the goals and needs of a given organization, we create dedicated systems and applications that let businesses grow.<br>
Our team is fluent in **Polish, English, German and, French**. That is why our cooperation with clients from all over the world is smooth.

**Some numbers from BitBag regarding Sylius:**
- 70+ **experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
- 150+ projects **delivered** on top of Sylius,
- 30+ **countries** of BitBag’s customers,
- 7+ **years** in the Sylius ecosystem.

**Our services:**
- Business audit/Consulting in the field of **strategy** development,
- Data/shop **migration**,
- Headless **eCommerce**,
- Personalized **software** development,
- **Project** maintenance and long term support,
- Technical **support**.

**Key clients:** Mollie, Guave, P24, Folkstar, i-LUNCH, Elvi Project, WestCoast Gifts.

---

If you need some help with Sylius development, don't be hesitated to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch) or send us an e-mail at hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2021/08/sylius-badges-transparent-wide.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)

## Community

---- 

For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius Shop

---

We created a demo app with some useful use-cases of plugins!
Visit [sylius-demo.bitbag.io](https://sylius-demo.bitbag.io/) to take a look at it. The admin can be accessed under
[sylius-demo.bitbag.io/admin/login](https://sylius-demo.bitbag.io/admin/login) link and `bitbag: bitbag` credentials.
Plugins that we have used in the demo:

| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL Plugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
| Braintree Plugin | https://github.com/BitBagCommerce/SyliusBraintreePlugin |https://plugins.sylius.com/plugin/braintree-plugin/|
| CMS Plugin | https://github.com/BitBagCommerce/SyliusCmsPlugin | https://plugins.sylius.com/plugin/cmsplugin/|
| Elasticsearch Plugin | https://github.com/BitBagCommerce/SyliusElasticsearchPlugin | https://plugins.sylius.com/plugin/2004/|
| Mailchimp Plugin | https://github.com/BitBagCommerce/SyliusMailChimpPlugin | https://plugins.sylius.com/plugin/mailchimp/ |
| Multisafepay Plugin | https://github.com/BitBagCommerce/SyliusMultiSafepayPlugin |
| Wishlist Plugin | https://github.com/BitBagCommerce/SyliusWishlistPlugin | https://plugins.sylius.com/plugin/wishlist-plugin/|
| **Sylius' Plugin** | **GitHub** | **Sylius' Store** |
| Admin Order Creation Plugin | https://github.com/Sylius/AdminOrderCreationPlugin | https://plugins.sylius.com/plugin/admin-order-creation-plugin/ |
| Invoicing Plugin | https://github.com/Sylius/InvoicingPlugin | https://plugins.sylius.com/plugin/invoicing-plugin/ |
| Refund Plugin | https://github.com/Sylius/RefundPlugin | https://plugins.sylius.com/plugin/refund-plugin/ |

**If you need an overview of Sylius' capabilities, schedule a consultation with our expert.**

[![](https://bitbag.io/wp-content/uploads/2020/10/button_free_consulatation-1.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)

## Additional resources for developers

---
To learn more about our contribution workflow and more, we encourage you to use the following resources:
* [Sylius Documentation](https://docs.sylius.com/en/latest/)
* [Sylius Contribution Guide](https://docs.sylius.com/en/latest/contributing/)
* [Sylius Online Course](https://sylius.com/online-course/)


## License

---

This plugin's source code is completely free and released under the terms of the MIT license.

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen.)

## Contact

---
If you want to contact us, the best way is to fill the form on [our website](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch) or send us an e-mail to hello@bitbag.io with your question(s). We guarantee that we answer as soon as we can!

[![](https://bitbag.io/wp-content/uploads/2021/08/badges-bitbag.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)
