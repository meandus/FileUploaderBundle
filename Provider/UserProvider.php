<?php

namespace QoopLmao\FileUploaderBundle\Provider;

use QoopLmao\FileUploaderBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * The security context
     *
     * @var SecurityContextInterface
     */
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * Gets the current authenticated user
     *
     * @return UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function getAuthenticatedUser()
    {
        $user = $this->securityContext->getToken()->getUser();

        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException('Must be logged in with a UserInterface instance');
        }

        return $user;
    }

    /**
     * Checks for roles granted to current user
     *
     * @param $role
     * @return bool
     */
    public function isGranted($role)
    {
        return $this->securityContext->isGranted($role);
    }
}