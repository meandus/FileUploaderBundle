<?php

namespace QoopLmao\FileUploaderBundle\Model;

interface UploadManagerInterface
{
    /**
     * Returns an empty upload instance
     *
     * @return UploadInterface
     */
    public function createUpload();

    /**
     * Persists upload instance to database
     *
     * @param UploadInterface $upload
     * @param bool $andFlush
     */
    public function updateUpload(UploadInterface $upload, $andFlush = true);

    public function updateMultipleUploads(array $uploads, $andFlush = true);

    public function removeUpload(UploadInterface $upload, $andFlush = true);

    public function findAllUploads();

    public function findUploadById($id, $lockMode = LockMode::NONE, $lockVersion = null);

    public function getUpload($id);

    public function findAllUploadsByUser(UserInterface $user, $orderby = null, $order = null);
}