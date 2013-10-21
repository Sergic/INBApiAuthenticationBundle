<?php

namespace INB\Bundle\ApiAuthenticationBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

use INB\Bundle\ApiAuthenticationBundle\Security\Authentication\Token\ApiUserToken;

class ApiListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, AuthenticationEntryPointInterface $authenticationEntryPoint)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->authenticationEntryPoint = $authenticationEntryPoint;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $apiToken = $request->get('token');
        if(!$apiToken) return;

        $token = new ApiUserToken();
        $token->setApiToken($apiToken);
        $token->setUser('username_'.time());

        try {
            $authToken = $this->authenticationManager->authenticate($token);
            $this->securityContext->setToken($authToken);

            return;
        } catch (AuthenticationException $failed) {

            $event->setResponse($this->authenticationEntryPoint->start($request, $failed));
        }
    }
}