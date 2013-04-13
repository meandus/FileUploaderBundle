<?php

namespace QoopLmao\FileUploaderBundle\Event\Event;

use QoopLmao\FileUploaderBundle\Model\UploadInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class UploadEvent extends Event
{
    private $upload;

    private $request;

    public function __construct(UploadInterface $upload, Request $request)
    {
        $this->upload = $upload;
        $this->request = $request;
    }

    public function getUpload()
    {
        return $this->upload;
    }

    public function getRequest()
    {
        return $this->request;
    }
}