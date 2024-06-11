<?php

declare(strict_types=1);

use Drjele\Symfony\Console\Command\CronjobCreateCommand;
use Drjele\Symfony\Console\Command\WorkerCreateCommand;
use Drjele\Symfony\Console\DependencyInjection\DrjeleSymfonyConsoleExtension;
use Drjele\Symfony\Console\Service\ConfGenerateService;
use Drjele\Symfony\Console\Template\CrontabTemplate;
use Drjele\Symfony\Console\Template\KubernetesCronjobTemplate;
use Drjele\Symfony\Console\Template\KubernetesWorkerTemplate;
use Drjele\Symfony\Console\Template\SupervisorTemplate;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set(CronjobCreateCommand::class)
        ->arg('$confGenerateService', new Reference(ConfGenerateService::class))
        ->arg('$config', '%drjele_symfony_console.cronjob%')
        ->tag('console.command');

    $services->set(WorkerCreateCommand::class)
        ->arg('$confGenerateService', new Reference(ConfGenerateService::class))
        ->arg('$config', '%drjele_symfony_console.worker%')
        ->tag('console.command');

    $services->set(ConfGenerateService::class)
        ->arg('$templates', new TaggedIteratorArgument(DrjeleSymfonyConsoleExtension::CONSOLE_TEMPLATE));

    $services->set(CrontabTemplate::class)
        ->tag(DrjeleSymfonyConsoleExtension::CONSOLE_TEMPLATE);

    $services->set(SupervisorTemplate::class)
        ->tag(DrjeleSymfonyConsoleExtension::CONSOLE_TEMPLATE);

    $services->set(KubernetesCronjobTemplate::class)
        ->tag(DrjeleSymfonyConsoleExtension::CONSOLE_TEMPLATE);

    $services->set(KubernetesWorkerTemplate::class)
        ->tag(DrjeleSymfonyConsoleExtension::CONSOLE_TEMPLATE);
};
