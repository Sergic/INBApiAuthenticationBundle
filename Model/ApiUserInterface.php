<?php

namespace INB\Bundle\ApiAuthenticationBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface ApiUserInterface extends UserInterface
{
    public function getToken();

    public function setToken($token);
}