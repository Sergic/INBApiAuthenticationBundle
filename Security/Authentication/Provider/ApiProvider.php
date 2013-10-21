<?php

namespace INB\Bundle\ApiAuthenticationBundle\Security\Authentication\Provider;

use INB\Bundle\ApiAuthenticationBundle\Model\ApiUserProviderInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\NonceExpiredException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

use Symfony\Component\Security\Core\User\UserInterface;

use INB\Bundle\ApiAuthenticationBundle\Security\Authentication\Token\ApiUserToken;

class ApiProvider
{
    private $userProvider;
    private $lifetime;

    public function __construct(ApiUserProviderInterface $userProvider, $lifetime)
    {
        $this->userProvider = $userProvider;
        $this->lifetime = $lifetime;
    }

    public function authenticate(ApiUserToken $token)
    {
        if (!$token->getApiToken()) {
            throw new AuthenticationException('Token api is empty.');
        }

        $token->setAttribute('lifetime', $this->lifetime);

        $user = $this->userProvider->loadUserByApiToken($token->getApiToken());

        if ($user) {
            $authenticatedToken = new ApiUserToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The Api authentication failed.');
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof ApiUserToken;
    }
}