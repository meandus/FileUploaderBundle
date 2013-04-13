<?php

namespace QoopLmao\FileUploaderBundle\Event\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FilterFilesUploadResponseEvent extends FilesUploadEvent
{
    private $response;

    public function __construct(array $uploads, Request $request, Response $response)
    {
        parent::__construct($uploads, $request);
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