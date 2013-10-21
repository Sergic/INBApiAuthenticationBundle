<?php

namespace INB\Bundle\ApiAuthenticationBundle;

use INB\Bundle\ApiAuthenticationBundle\DependencyInjection\Security\Factory\ApiFactory;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class INBApiAuthenticationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new ApiFactory());
    }
}
