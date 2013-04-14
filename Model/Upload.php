<?php

namespace QoopLmao\FileUploaderBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

ini_set('auto_detect_line_endings', true);

abstract class Upload implements UploadInterface
{
    protected $id;

    /**
     * @Assert\File(maxSize="6000000")
     *
     * @var UploadedFile
     */
    protected $file;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $info;

    /**
     * @var string
     */
    protected $mimetype;

    /**
     * @var string
     */
    protected $filesize;

    /**
     * @var string
     */
    protected $originalFilename;

    /**
     * @var string
     */
    protected $storedFilename;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var \DateTime
     */
    protected $datecreated;

    /**
     * @var \DateTime
     */
    protected $dateupdated;

    public function __construct()
    {

    }

    /**
     * Get id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set file
     *
     * @param UploadedFile $file
     * @return Upload
     */
    public function setFile(UploadedFile $file)
    {
        /** @var $file UploadedFile */
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set status
     *
     * @param $status
     * @return Upload
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set info
     *
     * @param $info
     * @return Upload
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set mimetype
     *
     * @param $mimetype
     * @return Upload
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;

        return $this;
    }

    /**
     * Get mimetype
     *
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * Set filesize
     *
     * @param $filesize
     * @return Upload
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;

        return $this;
    }

    /**
     * Get filesize
     *
     * @return string
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Set storedFilename
     *
     * @param $storedFilename
     * @return Upload
     */
    public function setStoredFilename($storedFilename)
    {
        $this->storedFilename = $storedFilename;

        return $this;
    }

    /**
     * Get storedFilename
     *
     * @return string
     */
    public function getStoredFilename()
    {
        return $this->storedFilename;
    }

    /**
     * Set originalFilename
     *
     * @param $originalFilename
     * @return Upload
     */
    public function setOriginalFilename($originalFilename)
    {
        $this->originalFilename = $originalFilename;

        return $this;
    }

    /**
     * Get originalFilename
     *
     * @return string
     */
    public function getOriginalFilename()
    {
        return $this->originalFilename;
    }

    /**
     * Set datecreated
     *
     * @param $datecreated
     * @return Upload
     */
    public function setDatecreated($datecreated)
    {
        $this->datecreated = $datecreated;

        return $this;
    }

    /**
     * Get datecreated
     *
     * @return string
     */
    public function getDatecreated()
    {
        return $this->datecreated;
    }

    /**
     * Set dateupdated
     *
     * @param $dateupdated
     * @return Upload
     */
    public function setDateupdated($dateupdated)
    {
        $this->dateupdated = $dateupdated;

        return $this;
    }

    /**
     * Get dateupdated
     *
     * @return string
     */
    public function getDateupdated()
    {
        return $this->dateupdated;
    }

    /**
     * Get absolute path for uploaded file
     *
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->storedFilename
            ? null
            : $this->getUploadRootDir().'/'.$this->storedFilename;
    }

    /**
     * Get web path
     *
     * NOT IN USE
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->storedFilename
            ? null
            : $this->getUploadDir().'/'.$this->storedFilename;
    }

    /**
     * Get upload directory for uploaded file
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web'.$this->getUploadDir();
    }

    /**
     * Get web upload directory
     *
     * NOT IN USE
     *
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return '/media/uploads';
    }

    protected function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    protected function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get file extension
     *
     * @return null|string
     */
    protected function guessExtension()
    {
        $filename = explode('.', $this->file->getClientOriginalName());

        if (count($filename) > 0)
        {
            return $filename[count($filename) - 1];
        }
        else
        {
            return $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            $this->setExtension($this->guessExtension());

            // do whatever you want to generate a unique name
            $filename = uniqid(mt_rand(), true);
            $this->storedFilename = $filename . '.' . $this->getExtension();
            $this->originalFilename = $this->file->getClientOriginalName();
            $this->filesize = $this->file->getClientSize();
            $this->mimetype = $this->file->getMimeType();
            $this->status = 'live';
            $this->datecreated = new \DateTime();
            $this->extension = $this->getExtension();
        }

        $this->dateupdated = new \DateTime();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->storedFilename);

        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (file_exists($this->getAbsolutePath())) {
            unlink($this->getAbsolutePath());

            return true;
        }

        return false;
    }
}
