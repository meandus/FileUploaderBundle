<?php

namespace QoopLmao\FileUploaderBundle\Event\Listener;

use QoopLmao\FileUploaderBundle\Event\Event\FormEvent;
use QoopLmao\FileUploaderBundle\QoopLmaoFileUploaderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class FailedUploadRedirectListener implements EventSubscriberInterface
{
    private $router;

    private $session;

    public function __construct(UrlGeneratorInterface $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return array(
            QoopLmaoFileUploaderEvents::UPLOAD_INITIALIZE => array(
                array('onFileUploadInitialize', 50),
            ),
        );
    }

    public function onFileUploadInitialize(FormEvent $event)
    {
        $form = $event->getForm();
        $request = $event->getRequest();

        if ($request->get('json'))
        {
            return;
        }

        if ('POST' === $request->getMethod())
        {
            $form->bind($request);

            if (!$form->isValid())
            {
                $this->session->getFlashBag()->add('alert', 'There was a problem uploading your file, please try again');
                $url = $this->router->generate('qoop_lmao_file_uploader_list');
                $response = new RedirectResponse($url);

                $event->setResponse($response);
            }
        }
    }
}