<?php
/**
 * @author    oliverde8<oliverde8@gmail.com>
 * @category  kit-v2-symfony
 */

namespace oliverde8\ComfyEasyAdminBundle;

use oliverde8\ComfyBundle\DependencyInjection\Compiler\ConfigPass;
use oliverde8\ComfyBundle\DependencyInjection\Compiler\LocaleScopePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class oliverde8ComfyEasyAdminBundle extends Bundle
{

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
