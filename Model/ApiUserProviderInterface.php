<?php

namespace INB\Bundle\ApiAuthenticationBundle\Model;

use INB\Bundle\ApiAuthenticationBundle\Exception\ApiTokenNotFoundException;
use INB\Bundle\ApiAuthenticationBundle\Model\ApiUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

interface ApiUserProviderInterface
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

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user);

    /**
     * Whether this provider supports the given user class
     *
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class);
}
