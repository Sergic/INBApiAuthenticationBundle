<?php

namespace INB\Bundle\ApiAuthenticationBundle\Security\Http\EntryPoint;

use
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Security\Core\Exception\AuthenticationException,
    Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface,
    Symfony\Component\Security\Http\HttpUtils,
    Symfony\Component\HttpKernel\HttpKernelInterface
;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ApiAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private $httpKernel;
    private $httpUtils;

    /**
     * Constructor
     *
     * @param HttpKernelInterface $kernel
     * @param HttpUtils           $httpUtils  An HttpUtils instance
     */
    public function __construct(HttpKernelInterface $kernel, HttpUtils $httpUtils)
    {
        $this->httpKernel = $kernel;
        $this->httpUtils = $httpUtils;
    }

    /**
     * {@inheritdoc}
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        if($authException){
            throw new AccessDeniedException();
        }
        else{
            return new Response('Authentication error', 403);
        }
    }
}