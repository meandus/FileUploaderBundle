<?php

namespace QoopLmao\FileUploaderBundle\Event\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FilesUploadEvent extends Event
{
    private $uploads;

    private $request;

    public function __construct(array $uploads, Request $request)
    {
        $this->uploads = $uploads;
        $this->request = $request;
    }

    public function getUploads()
    {
        return $this->uploads;
    }

    public function getRequest()
    {
        return $this->request;
    }
}