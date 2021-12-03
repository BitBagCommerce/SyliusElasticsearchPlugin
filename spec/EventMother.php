<?php

namespace spec\BitBag\SyliusElasticsearchPlugin;

use Elastica\Document;
use FOS\ElasticaBundle\Event\PostTransformEvent;
use PhpSpec\Wrapper\Collaborator;

class EventMother
{
    public static function createPostTransformEvent(Collaborator $documentCollaborator): PostTransformEvent
    {
        /**
         * @var Document $document
         */
        $document = $documentCollaborator->getWrappedObject();
        return new PostTransformEvent($document, [], new \stdClass());
    }
}