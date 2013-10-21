<?php

namespace INB\Bundle\ApiAuthenticationBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;


class ApiFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('inb_api_auth.security.authentication.provider'))
            ->addArgument('provider')->replaceArgument(0, new Reference($userProvider))
            ->addArgument('lifetime')->replaceArgument(1, $config['lifetime'])
        ;

        $listenerId = 'security.authentication.listener.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('inb_api_auth.security.authentication.listener'));
        $entryPointId = $this->createEntryPoint($container, $id, $config, $defaultEntryPoint);
        return array($providerId, $listenerId, $entryPointId);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'api';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('lifetime')->defaultValue(300)
            ->end();
    }

    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        if (null !== $defaultEntryPoint) {
            return $defaultEntryPoint;
        }

        $entryPointId = 'security.authentication.api_entry_point.'.$id;
        $container
            ->setDefinition($entryPointId, new DefinitionDecorator('inb_api_auth.security.authentication.entry_point'))
        ;

        return $entryPointId;
    }
}