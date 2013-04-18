<?php

namespace QoopLmao\FileUploaderBundle\Event\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use QoopLmao\FileUploaderBundle\Model\UploadInterface;

class EntityPostLoadListener implements EventSubscriber
{
    private $webroot;

    public function __construct($webroot)
    {
        $this->webroot = $webroot;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postLoad',
            'prePersist',
        );
    }

    public function postLoad(LifecycleEventArgs $event)
    {
        $this->updateUploadEntity($event);
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->updateUploadEntity($event);
    }

    private function updateUploadEntity(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof UploadInterface)
        {
            /** @var $entity \QoopLmao\FileUploaderBundle\Model\UploadInterface */
            $entity->setWebroot($this->webroot);
        }
    }
}