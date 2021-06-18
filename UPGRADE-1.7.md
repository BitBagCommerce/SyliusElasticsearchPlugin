# UPGRADE FROM 1.6 TO 1.7

* chrome headless as default behat session 

* sylius < 1.9 compatibility break. Main reason was a change in sylius behat context from "this product has text attribute" to "this product has a text attribute" and notation change 

* symfony <4.4 compatibility break. Main reason was removed the support for Symfony Templating component and changes in Form Factory Interface

* `Symfony\Component\BrowserKit\Client` was replaced by `Symfony\Component\BrowserKit\AbstractBrowser`

* `Doctrine\Common\Persistence\ObjectManager` was replaced by `Doctrine\ORM\EntityManagerInterface`

* `Symfony\Component\Debug\Debug` was replaced by `Symfony\Component\ErrorHandler\Debug`

* `tests/Application/config` was replaced by new config from `sylius/plugin-skeleton:1.9` 
  
* `Lakion\Behat\MinkDebugExtension` was replaced by  `FriendsOfBehat\MinkDebugExtension`

* If the configuration `src/Resources/config/services/event_listener.xml` has been overwritten then the following configuration must be used:
    ```
    <container xmlns="http://symfony.com/schema/dic/services" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">
        <services>
            <service id="bitbag_sylius_elasticsearch_plugin.event_listener.resource_index" class="BitBag\SyliusElasticsearchPlugin\EventListener\ResourceIndexListener">
                <argument type="service" id="bitbag.sylius_elasticsearch_plugin.refresher.resource" />
                <argument type="collection">
                    <argument type="collection">
                        <argument key="model">%sylius.model.product_attribute.class%</argument>
                        <argument key="serviceId">fos_elastica.object_persister.bitbag_attribute_taxons.default</argument>
                    </argument>
                    <argument type="collection">
                        <argument key="model">%sylius.model.product_option.class%</argument>
                        <argument key="serviceId">fos_elastica.object_persister.bitbag_option_taxons.default</argument>
                    </argument>
                    <argument type="collection">
                        <argument key="getParentMethod">getProduct</argument>
                        <argument key="model">%sylius.model.product.class%</argument>
                        <argument key="serviceId">fos_elastica.object_persister.bitbag_shop_product.default</argument>
                    </argument>
                </argument>
                <tag name="kernel.event_listener" event="sylius.product_attribute.post_create" method="updateIndex" />
                <tag name="kernel.event_listener" event="sylius.product_attribute.post_update" method="updateIndex" />
                <tag name="kernel.event_listener" event="sylius.option.post_create" method="updateIndex" />
                <tag name="kernel.event_listener" event="sylius.option.post_update" method="updateIndex" />
                <tag name="kernel.event_listener" event="sylius.product.post_create" method="updateIndex" />
                <tag name="kernel.event_listener" event="sylius.product.post_update" method="updateIndex" />
                <tag name="kernel.event_listener" event="sylius.product_variant.post_create" method="updateIndex" />
                <tag name="kernel.event_listener" event="sylius.product_variant.post_update" method="updateIndex" />
            </service>
            <service id="bitbag_sylius_elasticsearch_plugin.event_listener.order_products" class="BitBag\SyliusElasticsearchPlugin\EventListener\OrderProductsListener" public="true">
                <argument type="service" id="bitbag.sylius_elasticsearch_plugin.refresher.resource" />
                <argument type="string">fos_elastica.object_persister.bitbag_shop_product.default</argument>
                <tag name="kernel.event_listener" event="sylius.order.post_complete" method="updateOrderProducts" />
            </service>
        </services>
    </container>
    ```
* Trait `BitBag\SyliusElasticsearchPlugin\Model\ProductVariantTrait` should be used in an overridden ProductVariant entity class. [see how to overwrite a sylius model](https://docs.sylius.com/en/1.9/customization/model.html)
* Use `BitBag\SyliusElasticsearchPlugin\Model\ProductVariantInterface` interface in ProductVariant entity class.
    
    The final effect should look like the following:

    ```
    use BitBag\SyliusElasticsearchPlugin\Model\ProductVariantInterface as BitBagElasticsearchPluginVariant;
    use BitBag\SyliusElasticsearchPlugin\Model\ProductVariantTrait;
    use Sylius\Component\Core\Model\ProductInterface;
    use Sylius\Component\Core\Model\ProductVariantInterface as BaseProductVariantInterface;
    
    class ProductVariant extends BaseProductVariant implements BaseProductVariantInterface, BitBagElasticsearchPluginVariant
    {
        use ProductVariantTrait;
        
        ...
    }
    ```
        

