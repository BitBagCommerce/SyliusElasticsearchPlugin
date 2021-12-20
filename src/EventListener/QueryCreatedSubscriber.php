<?php
/*

This file was created by developers working at BitBag

Do you need more information about us and what we do? Visit our   website!

We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/
declare(strict_types=1);

namespace BitBag\SyliusElasticsearchPlugin\EventListener;

use BitBag\PluginCommonsPlugin\Event\QueryCreatedEvent;
use spec\Sylius\Component\Core\Test\Services\DefaultUnitedStatesChannelFactorySpec;
use Sylius\Component\Customer\Model\CustomerGroupInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use App\Context\CustomerGroupContext;

final class QueryCreatedSubscriber implements EventSubscriberInterface, QueryCreatedSubscriberInterface
{
    /** @var CustomerGroupContext $groupContext */
    public $groupContext;

    public function __construct(CustomerGroupContext $groupContext)
    {
        $this->groupContext = $groupContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            QueryCreatedEvent::NAME => 'onQueryCreated',
        ];
    }

    public function onQueryCreated(QueryCreatedEvent $event): void
    {
        /** @var BoolQuery $query */
        $query = $event->getQuery();

        $userGroupCode = $this->groupContext->getCustomerGroup()->getCode();

        $query->addMust(new \Elastica\Query\Terms(self::AUTHORIZED_GORUP_KEY,[$userGroupCode]));

    }
}
