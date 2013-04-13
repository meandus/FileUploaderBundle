<?php

namespace QoopLmao\FileUploaderBundle\Event\Event;

use QoopLmao\FileUploaderBundle\Model\UploadInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterUploadResponseEvent extends UploadEvent
{
    private $response;

    public function __construct(UploadInterface $upload, Request $request, Response $response)
    {
        parent::__construct($upload, $request);
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}