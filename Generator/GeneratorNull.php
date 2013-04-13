<?php

namespace QoopLmao\FileUploaderBundle\Generator;

use QoopLmao\FileUploaderBundle\Model\UploadInterface;

class GeneratorNull implements GeneratorInterface
{

    public function isImage(UploadInterface $upload)
    {
        return false;
    }

    public function getImageRoute(UploadInterface $upload)
    {
        return null;
    }

    public function getThumbnailResponse(UploadInterface $upload)
    {
        return null;
    }
}