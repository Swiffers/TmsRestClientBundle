<?php

namespace Tms\Bundle\RestClientBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register the hypermedia hydrators.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class RegisterHypermediaHydrators implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('tms_rest_client.hypermedia.hydratation_handler')) {
            return;
        }

        $definition = $container->getDefinition('tms_rest_client.hypermedia.hydratation_handler');

        $taggedServices = $container->findTaggedServiceIds(
            'tms_rest_client.hypermedia.hydrator'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
        	foreach ($tagAttributes as $attributes) {
	            $definition->addMethodCall(
    	            'setHydrator',
        	        array($attributes['id'], new Reference($id))
            	);
            }
        }
    }
}
