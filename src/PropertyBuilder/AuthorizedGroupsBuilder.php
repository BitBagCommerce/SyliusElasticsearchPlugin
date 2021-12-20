<?php
/*

This file was created by developers working at BitBag

Do you need more information about us and what we do? Visit our   website!

We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use Elastica\Document;
use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Core\Model\ProductInterface;

final class AuthorizedGroupsBuilder extends AbstractBuilder
{
    /** @var string */
    private $authorizedCustomerGroup;
    
    public function __construct(string $authorizedCustomerGroup)
    {
        $this->authorizedCustomerGroup = $authorizedCustomerGroup;
    }
    
    public function consumeEvent(TransformEvent $event): void
    {
        $this->buildProperty($event, ProductInterface::class,
            function (ProductInterface $product, Document $document): void {
                $authorizedGroups = [];

                foreach ($product->getAuthorizedCustomerGroups() as $group) {
                    $authorizedGroups[] = $group->getCode();
                }

                $document->set($this->authorizedCustomerGroup, $authorizedGroups);
            }
        );
    }
}
