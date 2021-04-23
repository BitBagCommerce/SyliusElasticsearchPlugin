# [![](https://bitbag.io/wp-content/uploads/2021/01/elasticsearch.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)

# BitBag SyliusElasticsearchPlugin

----

[![](https://img.shields.io/packagist/l/bitbag/elasticsearch-plugin.svg) ](https://packagist.org/packages/bitbag/elasticsearch-plugin "License") [ ![](https://img.shields.io/packagist/v/bitbag/elasticsearch-plugin.svg) ](https://packagist.org/packages/bitbag/elasticsearch-plugin "Version") [ ![](https://img.shields.io/github/workflow/status/BitBagCommerce/SyliusElasticsearchPlugin/Build) ](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/actions "Build status") [ ![](https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusElasticsearchPlugin.svg) ](https://scrutinizer-ci.com/g/BitBagCommerce/SyliusElasticsearchPlugin/ "Scrutinizer") [![](https://poser.pugx.org/bitbag/elasticsearch-plugin/downloads)](https://packagist.org/packages/bitbag/elasticsearch-plugin "Total Downloads") [![Slack](https://img.shields.io/badge/community%20chat-slack-FF1493.svg)](http://sylius-devs.slack.com) [![Support](https://img.shields.io/badge/support-contact%20author-blue])](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)

<p>
 <img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
</p>

At BitBag we do believe in open source. However, we are able to do it just because of our awesome clients, who are kind enough to share some parts of our work with the community. Therefore, if you feel like there is a possibility for us working together, feel free to reach us out. You will find out more about our professional services, technologies and contact details at [https://bitbag.io/](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch).

## Table of Content

***

* [Overview](#overview)
* [Support](#we-are-here-to-help)
* [Installation](#installation)
   * [Requirements](#requirements)
   * [Usage](#usage)
   * [Customization](#customization)
   * [Testing](#testing)
* [About us](#about-us)
   * [Community](#community)
* [Demo Sylius shop](#demo-sylius-shop)
* [Additional Sylius resources for developers](#additional-resources-for-developers)
* [License](#license)
* [Contact](#contact)

# Overview

----
Working [Sylius](https://sylius.com/) [Elasticsearch](https://www.elastic.co/products/elasticsearch) integration based on [FOSElasticaBundle](https://github.com/FriendsOfSymfony/FOSElasticaBundle). The main goal of this plugin is to support filtering products by options, attributes, taxons, channels and name in the front product list page. It totally replaces the default Sylius `sylius_shop_product_index` route.

If you are curious about the details of this plugin, read [this Medium blog post](https://medium.com/@BitBag/using-sylius-and-elasticsearch-for-a-products-filter-6dc9f0ce929).

What is more, the plugin has a nice Sylius-oriented architecture that allows mapping resources to the Elastic document easier. It is flexible as well, so that you can customize the existing features for your specific business needs.


## We are here to help
This **open-source plugin was developed to help the Sylius community** and make Mollie payments platform available to any Sylius store. If you have any additional questions, would like help with installing or configuring the plugin or need any assistance with your Sylius project - let us know!

[![](https://bitbag.io/wp-content/uploads/2020/10/button-contact.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)


# Installation

----

### Requirements

We work on stable, supported and up-to-date versions of packages. We recommend you to do the same.
If you use Sylius 1.4, you might get a compatibility issue for Pagerfanta. Please read [this issue](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/issues/23) in order to proceed with a workaround.

*Note*: This Plugin currently supports ElasticSearch 5.3.x up to 6.8.x.  ElasticSearch ^7.x is not currently supported.

```bash
$ composer require bitbag/elasticsearch-plugin
```


Add plugin dependencies to your `config/bundles.php` file:
```php
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
        default: { host: localhost, port: 9200 }
    indexes:
        app: ~
```
should become:
```

fos_elastica:
    clients:
        default: { host: localhost, port: 9200 }
```
In the end, with an elasticsearch server running, execute following command:
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

1. If you haven't done it yet, create two files:
   * `_horizontalMenu.html.twig` in `templates/bundles/SyliusShopBundle/Taxon` directory
   * `_breadcrumb.html.twig` in `templates/bundles/SyliusShopBundle/Product/Show` directory
2. Paste into those files content of respectively `vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Resources/views/Taxon/_horizontalMenu.html.twig` and `vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Resources/views/Product/Show/_breadcrumb.html.twig` files, replacing `sylius_shop_product_index` with `bitbag_sylius_elasticsearch_plugin_shop_list_products` in both of them.
3. Clean your cache with `bin/console cache:clear` command.
4. :tada:

If you're using vertical menu - follow steps above with `_verticalMenu.html.twig` file instead. It's in the same directory as the `horizontal_menu.html.twig` file.

**Be aware! Elasticsearch does not handle dashes well. This plugin depends on the code field in Sylius resources. Please use underscores instead of dashes in your code fields.**

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

### Site-wide search

This plugin offers a site-wide search feature as well. You have a search box field where you query all products indexed on ElasticSearch. When you enter a query in the search box the results will appear in the search results page.

You can also add search facets (a.k.a. filters) to your search results page. To do so you have to add facets to the `bitbag_sylius_elasticsearch_plugin.facet.registry` service (see an example of this service definition [here](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/tests/Application/config/services.yaml)). A facet is a service which implements the `BitBag\SyliusElasticsearchPlugin\Facet\FacetInterface`. You can implement your own facets from scratch or you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) one of the basic facet implementation included in this plugin, which are:

* [`TaxonFacet`](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/src/Facet/TaxonFacet.php) which allows to filter your search results by taxons using the ElasticSearch [`Terms`](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html) aggregation.
* [`AttributeFacet`](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/src/Facet/AttributeFacet.php) which allows to filter your search results by product attributes values using the ElasticSearch [`Terms`](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html) aggregation.
* [`OptionFacet`](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/src/Facet/OptionFacet.php) which is the same as `AttributeFacet` but for product options.
* [`PriceFacet`](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/src/Facet/PriceFacet.php) which allows to filter search results by price range the ElasticSearch [`Histogram`](https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-histogram-aggregation.html) aggregation.

You can see an example of the definition of all of these facets [here](https://github.com/BitBagCommerce/SyliusElasticsearchPlugin/blob/master/tests/Application/config/services.yaml).

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
$ yarn install
$ yarn build
$ bin/console assets:install public -e test
$ bin/console doctrine:schema:create -e test
$ bin/console server:run 127.0.0.1:8080 -d public -e test
$ elasticsearch
$ open http://localhost:8080
$ vendor/bin/behat
$ vendor/bin/phpspec run
```


# About us

---

BitBag is an agency that provides high-quality **eCommerce and Digital Experience software**. Our main area of expertise includes eCommerce consulting and development for B2C, B2B, and Multi-vendor Marketplaces.
The scope of our services related to Sylius includes:
- **Consulting** in the field of strategy development
- Personalized **headless software development**
- **System maintenance and long-term support**
- **Outsourcing**
- **Plugin development**
- **Data migration**

Some numbers regarding Sylius:
* **20+ experts** including consultants, UI/UX designers, Sylius trained front-end and back-end developers,
* **100+ projects** delivered on top of Sylius,
* Clients from  **20+ countries**
* **3+ years** in the Sylius ecosystem.

---

If you need some help with Sylius development, don't be hesitate to contact us directly. You can fill the form on [this site](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch) or send us an e-mail to hello@bitbag.io!

---

[![](https://bitbag.io/wp-content/uploads/2020/10/badges-sylius.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)

## Community

----
For online communication, we invite you to chat with us & other users on [Sylius Slack](https://sylius-devs.slack.com/).

# Demo Sylius shop

---

We created a demo app with some useful use-cases of plugins!
Visit b2b.bitbag.shop to take a look at it. The admin can be accessed under b2b.bitbag.shop/admin/login link and sylius: sylius credentials.
Plugins that we have used in the demo:

| BitBag's Plugin | GitHub | Sylius' Store|
| ------ | ------ | ------|
| ACL PLugin | *Private. Available after the purchasing.*| https://plugins.sylius.com/plugin/access-control-layer-plugin/|
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
To learn more about our contribution workflow and more, we encourage ypu to use the following resources:
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

[![](https://bitbag.io/wp-content/uploads/2020/10/footer.png)](https://bitbag.io/contact-us/?utm_source=github&utm_medium=referral&utm_campaign=plugins_elasticsearch)
