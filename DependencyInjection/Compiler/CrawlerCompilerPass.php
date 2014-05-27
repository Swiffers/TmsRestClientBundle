<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

class CrawlerCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('tms_rest_client.crawler') || !$container->hasDefinition('tms_rest_client.crawler.handler') ) {
            return;
        }

        $configuration = $container->getParameter('tms_rest_client.configuration');
        $crawlersConfiguration = $configuration['crawlers'];
    
        $handlerDefinition = $container->getDefinition('tms_rest_client.crawler.handler');

        foreach($crawlersConfiguration as $crawlerName => $crawlerConfiguration) {
            foreach($crawlerConfiguration['resources'] as $crawlerResourceName => $crawlerResource) {
                $crawlerDefinition = new DefinitionDecorator('tms_rest_client.crawler');
                $crawlerDefinition->replaceArgument(0, new Reference($crawlerConfiguration['da_api_client']));
                $crawlerDefinition->replaceArgument(1, $crawlerName);
                $crawlerDefinition->replaceArgument(2, $crawlerResource);

                $crawlerServiceId = sprintf(
                    "tms_rest_client.crawlers.%s.%s",
                    $crawlerName,
                    $crawlerResourceName
                );
                $container->setDefinition($crawlerServiceId, $crawlerDefinition);
        
                $handlerDefinition->addMethodCall(
                    'setCrawler',
                    array(new Reference($crawlerServiceId), $crawlerServiceId)
                );
            }
        }
    }
}
