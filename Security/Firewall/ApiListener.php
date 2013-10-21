<?php

namespace INB\Bundle\ApiAuthenticationBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

use INB\Bundle\ApiAuthenticationBundle\Security\Authentication\Token\ApiUserToken;

class ApiListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
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
        } catch (AuthenticationException $failed) {
            // ... you might log something here

            // To deny the authentication clear the token. This will redirect to the login page.
            // $this->securityContext->setToken(null);
            // return;

            // Deny authentication with a '403 Forbidden' HTTP response
            //$event->setResponse($this->getForbiddenResponse());
            return;
        }
        return;
    }

//    private function getForbiddenResponse()
//    {
//        $response = new Response();
//        $response->setStatusCode(403);
//        $response->setContent(json_encode(array(
//            'success' => 0,
//            'errors' => array(
//                array(
//                    'code' => 403,
//                    'exception_code' => 403,
//                    'message' => 'Forbidden',
//                )
//            )
//        )));
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;
//    }
}