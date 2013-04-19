<?php

namespace QoopLmao\FileUploaderBundle\Model;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;
use QoopLmao\FileUploaderBundle\Model\UploadInterface;

class UploadManager implements UploadManagerInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $repository;

    /**
     * Constructor.
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->class = $class;
        $this->repository = $this->em->getRepository($class);
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Returns an empty upload instance
     *
     * @return UploadInterface
     */
    public function createUpload()
    {
        $class = $this->getClass();
        $upload = new $class;

        return $upload;
    }

    /**
     * Persists upload instance to database
     *
     * @param UploadInterface $upload
     * @param bool $andFlush
     */
    public function updateUpload(UploadInterface $upload, $andFlush = true)
    {
        $this->em->persist($upload);

        if ($andFlush)
        {
            $this->em->flush();
        }
    }

    public function updateMultipleUploads(array $uploads, $andFlush = true)
    {
        foreach ($uploads as $upload)
        {
            $this->em->persist($upload);
        }

        if ($andFlush)
        {
            $this->em->flush();
        }
    }

    public function removeUpload(UploadInterface $upload, $andFlush = true)
    {
        $this->em->remove($upload);

        if ($andFlush)
        {
            $this->em->flush();
        }
    }

    public function findAllUploads()
    {
        $qb = $this->repository->createQueryBuilder('u')
            ->orderBy('u.datecreated', 'desc')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function findUploadById($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->repository->find($id, $lockMode, $lockVersion);
    }

    public function getUpload($id)
    {
        $upload = $this->findUploadById($id);

        if ($upload)
        {
            return $upload;
        }

        return $this->createUpload();
    }

    public function findAllUploadsByUser(UserInterface $user, $orderby = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy(array('user' => $user->getId()), $orderby, $limit, $offset);
    }
}
