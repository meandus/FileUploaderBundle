<?php

namespace QoopLmao\FileUploaderBundle\Provider;

use QoopLmao\FileUploaderBundle\Model\UserInterface;

interface UserProviderInterface
{
    /**
     * Gets the current authenticated user
     *
     * @return UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function getAuthenticatedUser();

    /**
     * Checks for roles granted to current user
     *
     * @param $role
     * @return bool
     */
    public function isGranted($role);
}