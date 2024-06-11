<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class DrjeleSymfonyConsoleExtension extends Extension
{
    public const CONSOLE_TEMPLATE = 'drjele.symfony.console.template';

    public function load(array $configs, ContainerBuilder $containerBuilder): void
    {
        $loader = new PhpFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $containerBuilder->setParameter('drjele_symfony_console.cronjob', $config[Configuration::CRONJOB] ?? null);
        $containerBuilder->setParameter('drjele_symfony_console.worker', $config[Configuration::WORKER] ?? null);
    }
}
