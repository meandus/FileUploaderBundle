<?php

namespace QoopLmao\FileUploaderBundle\Generator;

use QoopLmao\FileUploaderBundle\Model\UploadInterface;

class GeneratorAdapter
{
    private $generator;

    public function __construct(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function isImage(UploadInterface $upload)
    {
        return $this->generator->isImage($upload);
    }

    public function getImageRoute(UploadInterface $upload)
    {
        return $this->generator->getImageRoute($upload);
    }

    public function getThumbnailResponse(UploadInterface $upload)
    {
        return $this->generator->getThumbnailResponse($upload);
    }
}