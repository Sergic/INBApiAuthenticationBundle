<?php

namespace INB\Bundle\ApiAuthenticationBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * ApiTokenNotFoundException is thrown if a User cannot be found by its api token.
 */
class ApiTokenNotFoundException extends AuthenticationException
{
    private $apiToken;

    /**
     * {@inheritDoc}
     */
    public function getMessageKey()
    {
        return 'Api token could not be found.';
    }

    /**
     * Get the api token.
     *
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * Set the api token.
     *
     * @param string $token
     */
    public function setApiToken($token)
    {
        $this->apiToken = $token;
    }

    /**
     * {@inheritDoc}
     */
    public function serialize()
    {
        return serialize(array(
                $this->apiToken,
                parent::serialize(),
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($str)
    {
        list($this->apiToken, $parentData) = unserialize($str);

        parent::unserialize($parentData);
    }
}