<?php

namespace Tms\Bundle\RestClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tms\Bundle\RestClientBundle\DependencyInjection\Compiler\BuildCrawlingPathPass;
use Tms\Bundle\RestClientBundle\DependencyInjection\Compiler\RegisterHypermediaHydrators;

/**
 * @author TESSI Marketing <contact@tessi.fr>
 */
class TmsRestClientBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new BuildCrawlingPathPass());
        $container->addCompilerPass(new RegisterHypermediaHydrators());
    }
}
