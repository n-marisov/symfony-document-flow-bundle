<?php

namespace Maris\Symfony\DocumentFlow;

use Maris\Symfony\DocumentFlow\DependencyInjection\DocumentFlowExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class DocumentFlowBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new DocumentFlowExtension();
    }
}