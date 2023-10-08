<?php

namespace Maris\Symfony\DocumentFlow\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class DocumentFlowExtension extends Extension
{
    /**
     * Загружаем файл конфигурации
     * @inheritDoc
     */
    public function load( array $configs, ContainerBuilder $container )
    {
      /*  $path = realpath( dirname(__DIR__).'/../config' );
        $loader = new YamlFileLoader( $container, new FileLocator( $path ) );
        $loader->load('services.yaml');*/
    }
}