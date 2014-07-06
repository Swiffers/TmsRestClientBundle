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
        if (!$container->hasDefinition('tms_rest_client.crawler')) {
            return;
        }

        $crawlerDefinition = $container->getDefinition('tms_rest_client.crawler');
        $abstractCrawlingPathDefinition = $container->getDefinition('tms_rest_client.crawling_path');

        $taggedServices = $container->findTaggedServiceIds(
            'da_api_client.api'
        );

        foreach ($taggedServices as $apiClientId => $tagAttributes) {
            $id = substr($apiClientId, strlen('da_api_client.api.'));
            $pathId = sprintf(
                'tms_rest_client.crawling_path.%s',
                $pathId
            );

            $crawlingPathDefinition = new DefinitionDecorator($abstractCrawlingPathDefinition);
            $crawlingPathDefinition->replaceArgument(0, new Reference($apiClientId))
            $container->setDefinition($pathId, $crawlingPathDefinition);

            $crawlerDefinition->addMethodCall(
                'addCrawlingPath',
                array($id, new Reference($pathId))
            );
        }
    }
}
