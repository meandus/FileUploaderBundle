<?php

namespace QoopLmao\FileUploaderBundle\Entity;

use Doctrine\Common\Persistence\ObjectManager;
use QoopLmao\FileUploaderBundle\Model\UploadInterface;
use QoopLmao\FileUploaderBundle\Model\UploadManager as BaseUploadManager;

/**
 * BC class for people extending it in their bundle.
 * TODO Remove this class on July 31st
 */
class UploadManager extends BaseUploadManager
{
    protected $em;

    public function __construct(ObjectManager $em, $class)
    {
        parent::__construct($em, $class);

        $this->em = $em;
    }
}
