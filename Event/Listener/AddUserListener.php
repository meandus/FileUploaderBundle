<?php

namespace QoopLmao\FileUploaderBundle\Event\Listener;

use QoopLmao\FileUploaderBundle\Event\Event\GetFilesUploadResponseEvent;
use QoopLmao\FileUploaderBundle\Provider\UserProviderInterface;
use QoopLmao\FileUploaderBundle\QoopLmaoFileUploaderEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddUserListener implements EventSubscriberInterface
{
    private $userProvider;

    private $addUser;

    public function __construct(UserProviderInterface $userProvider, $addUser)
    {
        $this->userProvider = $userProvider;
        $this->addUser = $addUser;
    }

    public static function getSubscribedEvents()
    {
        return array(
            QoopLmaoFileUploaderEvents::UPLOAD_SUCCESS => array(
                array('onFileUploadSuccess', 20),
            ),
        );
    }

    public function onFileUploadSuccess(GetFilesUploadResponseEvent $event)
    {
        if (!$this->addUser)
        {
            return;
        }

        $uploads = $event->getUploads();

        $user = $this->userProvider->getAuthenticatedUser();

        /** @var $upload \QoopLmao\FileUploaderBundle\Model\UploadInterface */
        foreach ($uploads as $upload)
        {
            $upload->setUser($user);
        }
    }
}