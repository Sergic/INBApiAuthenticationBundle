<?php

namespace INB\Bundle\ApiAuthenticationBundle\Model;

use INB\Bundle\ApiAuthenticationBundle\Exception\ApiTokenNotFoundException;
use INB\Bundle\ApiAuthenticationBundle\Model\ApiUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

interface ApiUserProviderInterface extends UserProviderInterface
{
    /**
     * Loads the user for the given api token.
     *
     * This method must throw ApiTokenNotFoundException if the user is not
     * found.
     *
     * @param string $token The Api token key
     *
     * @return ApiUserInterface
     *
     * @see ApiTokenNotFoundException
     *
     * @throws ApiTokenNotFoundException if the user is not found
     *
     */
    public function loadUserByApiToken($token);
}
