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
        if (!$container->hasDefinition('tms_rest_client.crawler')) {
            return;
        }

        $configuration = $container->getParameter('tms_rest_client.configuration');
        $crawlersConfiguration = $configuration['crawlers'];

        foreach($crawlersConfiguration as $crawlerName => $crawlerConfiguration) {
            $crawlerDefinition = new DefinitionDecorator('tms_rest_client.crawler');
            $crawlerDefinition->replaceArgument(0, new Reference($crawlerConfiguration['da_api_client']));

            $crawlerServiceId = sprintf("tms_rest_client.crawler.%s", $crawlerName);
            $container->setDefinition($crawlerServiceId, $crawlerDefinition);
        }
    }
}
