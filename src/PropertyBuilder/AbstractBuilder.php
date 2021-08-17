<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);
namespace BitBag\SyliusElasticsearchPlugin\PropertyBuilder;

use FOS\ElasticaBundle\Event\TransformEvent;
use Sylius\Component\Resource\Model\ToggleableInterface;

abstract class AbstractBuilder implements PropertyBuilderInterface
{
    public function buildProperty(TransformEvent $event, string $supportedModelClass, callable $callback): void
    {
        $model = $event->getObject();

        if (!$model instanceof $supportedModelClass || ($model instanceof ToggleableInterface && !$model->isEnabled())) {
            return;
        }

        $document = $event->getDocument();

        $callback($model, $document);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TransformEvent::POST_TRANSFORM => 'consumeEvent',
        ];
    }
}
