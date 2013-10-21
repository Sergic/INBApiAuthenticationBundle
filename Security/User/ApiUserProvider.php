<?php

namespace INB\Bundle\ApiAuthenticationBundle\Security\User;

use Doctrine\ORM\EntityRepository;
use INB\Bundle\ApiAuthenticationBundle\Model\ApiUserInterface;
use INB\Bundle\ApiAuthenticationBundle\Model\ApiUserProviderInterface;

use Symfony\Component\Security\Core\User\UserInterface;
use INB\Bundle\ApiAuthenticationBundle\Exception\ApiTokenNotFoundException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiUserProvider implements ApiUserProviderInterface
{
    /**
     * @var EntityRepository
     */
    protected $userRepository;

    public function setUserRepository(EntityRepository $repository)
    {
        $this->userRepository = $repository;
    }

    public function loadUserByApiToken($token)
    {
        $user = $this->userRepository->findOneBy(array('token' => $token));
        if (!$user) {
            throw new ApiTokenNotFoundException(sprintf('Token api "%s" does not exist.', $token));
        }

        return $user;
    }


    public function refreshUser(UserInterface $user)
    {
        $refreshedUser = $this->userRepository->findOneBy(array('id' => $user->getId()));
        if (null === $refreshedUser) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        if (!$user instanceof ApiUserInterface) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $refreshedUser;
        //return $this->loadUserByApiToken($user->getToken());
    }

    public function supportsClass($class)
    {
        return true;
    }
}