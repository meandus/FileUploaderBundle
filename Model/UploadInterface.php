<?php

namespace QoopLmao\FileUploaderBundle\Model;

use Doctrine\ORM\Mapping as ORM;

interface UploadInterface
{
    /**
     * Sets internally used webroot
     *
     * @param $webroot
     * @return $this
     */
    public function setWebroot($webroot);
}
