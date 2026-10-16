# UPGRADE FROM 5.x TO 6.0.0

1. Support for Sylius 2.3 and Symfony 8 has been added. The supported range is now Sylius `^2.2`,
   Symfony `^6.4 || ^7.4 || ^8.0` and PHP `^8.3`. Support for Sylius 2.0 and 2.1 has been dropped.

2. `friendsofsymfony/elastica-bundle` has been bumped to `^7.2` (`ruflin/elastica` `^8.0 || ^9.0`),
   so an **Elasticsearch 8.x or 9.x server is required**. Elasticsearch 7 is no longer supported.
   Composer picks the Elastica version that matches your server only if you restrict it, e.g. for Elasticsearch 8:

    ```bash
    composer require ruflin/elastica:^8.0
    ```

   After upgrading the server, rebuild the indexes:

    ```bash
    bin/console fos:elastica:populate
    ```

3. The FOSElastica client configuration format has changed: `url`, `host` and `port` were replaced by `hosts`.
   The plugin's own configuration already uses the new format and still reads `BITBAG_ES_HOST` / `BITBAG_ES_PORT`.
   If you override the client in your application, update it:

    ```diff
     fos_elastica:
         clients:
    -        default: { url: '%env(ELASTICSEARCH_URL)%' }
    +        default: { hosts: ['%env(ELASTICSEARCH_URL)%'] }
    ```

4. The plugin's service definitions have been migrated from XML to PHP, as Symfony 8 no longer supports XML service configuration.
   The `config/services.xml` file and the files in `config/services/` have been replaced with their `.php` counterparts.
   Service IDs and aliases have not changed. If you imported any of the plugin's service files directly,
   change the extension of the imported resource from `.xml` to `.php`.

5. The plugin's Behat configuration has been migrated from `behat.yml.dist` to `behat.dist.php`, and its suites and
   services from YAML/XML to PHP (`tests/Behat/Resources/suites.php`, `tests/Behat/Resources/services.php`).
   Step definitions now use PHP attributes instead of annotations.

6. Taxon codes are no longer lowercased when indexed (`product_taxons`, `option_taxons`) or queried
   (`ShopProductListDataHandler`, `ProductAttributesFinder`, `ProductOptionsFinder`, `ProductTaxonsMapper`).
   They are now stored and matched exactly as defined in Sylius. This fixes taxon facet labels on case-sensitive
   databases (e.g. PostgreSQL) and attribute filters for taxons with mixed-case codes.

   Rebuild the indexes after upgrading:

    ```bash
    bin/console fos:elastica:populate
    ```

   If you customised any of the classes above or query `product_taxons` / `option_taxons` / `attribute_taxons`
   yourself, stop lowercasing the taxon codes you pass in.
