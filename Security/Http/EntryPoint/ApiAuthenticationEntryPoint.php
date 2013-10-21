<?php

namespace INB\Bundle\ApiAuthenticationBundle\Security\Http\EntryPoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\HttpUtils;

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
        return $this->getForbiddenResponse();
    }

    private function getForbiddenResponse()
    {
        $response = new Response();
        $response->setStatusCode(403);
        $response->setContent(json_encode(array(
            'success' => 0,
            'errors' => array(
                array(
                    'code' => 403,
                    'exception_code' => 403,
                    'message' => 'Forbidden',
                )
            )
        )));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}