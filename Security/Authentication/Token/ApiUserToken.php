<?php

namespace INB\Bundle\ApiAuthenticationBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ApiUserToken extends AbstractToken
{
    public $created;

    public $nonce;

    public $apiToken;

    public function __construct(array $roles = array())
    {
        parent::__construct($roles);

        // If the user has roles, consider it authenticated
        $this->setAuthenticated(count($roles) > 0);
    }

    public function getCredentials()
    {
        return '';
    }

    public function getApiToken()
    {
        return $this->apiToken;
    }

    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;
    }
}