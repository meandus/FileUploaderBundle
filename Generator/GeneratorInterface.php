<?php

namespace QoopLmao\FileUploaderBundle\Generator;

use QoopLmao\FileUploaderBundle\Model\UploadInterface;

interface GeneratorInterface
{
    public function isImage(UploadInterface $upload);

    public function getImageRoute(UploadInterface $upload);

    public function getThumbnailResponse(UploadInterface $upload);
}