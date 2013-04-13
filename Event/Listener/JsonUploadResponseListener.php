<?php

namespace QoopLmao\FileUploaderBundle\Event\Listener;

use QoopLmao\FileUploaderBundle\Event\Event\FilterUploadResponseEvent;
use QoopLmao\FileUploaderBundle\Event\Event\FormEvent;
use QoopLmao\FileUploaderBundle\Event\Event\FilterFilesUploadResponseEvent;
use QoopLmao\FileUploaderBundle\Event\Event\GetFilesUploadResponseEvent;
use QoopLmao\FileUploaderBundle\Generator\GeneratorAdapter;
use QoopLmao\FileUploaderBundle\QoopLmaoFileUploaderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JsonUploadResponseListener implements EventSubscriberInterface
{
    private $router;

    private $generator;

    public function __construct(UrlGeneratorInterface $router, GeneratorAdapter $generator)
    {
        $this->router = $router;
        $this->generator = $generator;
    }

    public static function getSubscribedEvents()
    {
        return array(
            QoopLmaoFileUploaderEvents::UPLOAD_SUCCESS => 'onFileUploadSuccess',
            QoopLmaoFileUploaderEvents::UPLOAD_COMPLETED => 'onFileUploadCompleted',
            QoopLmaoFileUploaderEvents::UPLOAD_FAILED => 'onFileUploadFailed',
            QoopLmaoFileUploaderEvents::DELETE_COMPLETED => 'onFileDeleteCompleted',
        );
    }

    public function onFileUploadSuccess(GetFilesUploadResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->get('json'))
        {
            return;
        }

        $response = new Response();
        $event->setResponse($response);
    }

    public function onFileUploadCompleted(FilterFilesUploadResponseEvent $event)
    {
        $uploads = $event->getUploads();
        $request = $event->getRequest();

        if (!$request->get('json'))
        {
            return;
        }

        $json = array(
            'files' => array()
        );

        /** @var $upload \QoopLmao\FileUploaderBundle\Model\UploadInterface */
        foreach ($uploads as $upload)
        {
            $json['files'][] = array(
                'delete_type' => 'DELETE',
                'delete_url' => $this->router->generate('qoop_lmao_file_uploader_delete_json', array(
                    'id' => $upload->getId())
                ),
                'name' => $upload->getOriginalfilename(),
                'size' => $upload->getFilesize(),
                'thumbnail_url' => $this->generator->getImageRoute($upload),
                'type' => $upload->getMimetype(),
                'url' => $upload->getWebPath(),
            );
        }

        $response = new JsonResponse($json);

        $event->setResponse($response);
    }

    public function onFileUploadFailed(FormEvent $event)
    {
        $json = new JsonResponse(0);

        $event->setResponse($json);
    }

    public function onFileDeleteCompleted(FilterUploadResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->get('json'))
        {
            return;
        }

        $response = new JsonResponse(array('success' => true));

        $event->setResponse($response);
    }
}