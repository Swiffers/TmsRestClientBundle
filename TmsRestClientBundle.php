<?php

namespace Tms\Bundle\RestClientBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tms\Bundle\RestClientBundle\DependencyInjection\Compiler\BuildCrawlingPathPass;

/**
 * @author TESSI Marketing <contact@tessi.fr>
 */
class TmsRestClientBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new BuildCrawlingPathPass());
    }
}
