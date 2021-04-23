# UPGRADE FROM 1.6 TO 2.0

* chrome headless as default behat session 

* sylius < 1.9 compatibility break. Main reason was a change in sylius behat context from "this product has text attribute" to "this product has a text attribute" and notation change 

* symfony <5 compatibility break. Main reason was removed the support for Symfony's Templating component and changes in Form Factory Interface

* `Symfony\Component\BrowserKit\Client` was replaced by `Symfony\Component\BrowserKit\AbstractBrowser`

* `Doctrine\Common\Persistence\ObjectManager` was replaced by `Doctrine\ORM\EntityManagerInterface`

* `Symfony\Component\Debug\Debug` was replaced by `Symfony\Component\ErrorHandler\Debug`

* `tests/Application/config` was replaced by new config from `sylius/plugin-skeleton:1.9` 
  
* `Lakion\Behat\MinkDebugExtension` was replaced by  `FriendsOfBehat\MinkDebugExtension`

