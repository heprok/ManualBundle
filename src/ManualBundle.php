<?php

declare(strict_types=1);

namespace Tlc\ManualBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ManualBundle extends Bundle
{
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
    // /**
    //  * @param ContainerBuilder $container
    //  */
    // public function build(ContainerBuilder $container)
    // {
    //     $namespaces = ['Tlc\ManualBundle\Entity'];
    //     $directories = [__DIR__ . '/Entity'];
    //     $container->addCompilerPass(DoctrineOrmMappingsPass::createAnnotationMappingDriver($namespaces, $directories));
    // }
}