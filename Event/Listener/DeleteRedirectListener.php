<?php

namespace QoopLmao\FileUploaderBundle\Event\Listener;

use QoopLmao\FileUploaderBundle\Event\Event\FormEvent;
use QoopLmao\FileUploaderBundle\QoopLmaoFileUploaderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DeleteRedirectListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            QoopLmaoFileUploaderEvents::UPLOAD_INITIALIZE => 'onFileUploadInitialize',
        );
    }

    public function onFileUploadInitialize(FormEvent $event)
    {
        $request = $event->getRequest();

        if ($request->get('file-delete'))
        {
            $response =  new RedirectResponse($request->get('file-delete'));
            $event->setResponse($response);
        }
    }
}