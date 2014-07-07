<?php

namespace Tms\Bundle\RestClientBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

/**
 * Build the crawling path.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class BuildCrawlingPathPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('tms_rest_client.hypermedia.crawler')) {
            return;
        }

        $crawlerDefinition = $container->getDefinition('tms_rest_client.hypermedia.crawler');

        $taggedServices = $container->findTaggedServiceIds(
            'da_api_client.api'
        );

        foreach ($taggedServices as $apiClientId => $tagAttributes) {
            $id = substr($apiClientId, strlen('da_api_client.api.'));
            $pathId = sprintf(
                'tms_rest_client.hypermedia.crawling_path.%s',
                $id
            );

            $crawlingPathDefinition = new DefinitionDecorator('tms_rest_client.hypermedia.crawling_path');
            $crawlingPathDefinition->replaceArgument(1, new Reference('tms_rest_client.hypermedia.crawler'));
            $crawlingPathDefinition->replaceArgument(2, new Reference($apiClientId));
            $container->setDefinition($pathId, $crawlingPathDefinition);

            $crawlerDefinition->addMethodCall(
                'setCrawlingPath',
                array($id, new Reference($pathId))
            );
        }
    }
}
