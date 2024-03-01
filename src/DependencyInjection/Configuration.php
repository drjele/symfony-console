<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\DependencyInjection;

use Drjele\Symfony\Console\Template\CrontabTemplate;
use Drjele\Symfony\Console\Template\SupervisorTemplate;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const COMMAND = 'command';
    public const SCHEDULE = 'schedule';
    public const LOG = 'log';
    public const TEMPLATE_CLASS = 'template_class';
    public const CONF_FILES_DIR = 'conf_files_dir';
    public const LOGS_DIR = 'logs_dir';
    public const HEARTBEAT = 'heartbeat';
    public const DESTINATION_FILE = 'destination_file';
    public const CONFIG = 'config';
    public const COMMANDS = 'commands';
    public const MINUTE = 'minute';
    public const HOUR = 'hour';
    public const DAY_OF_MONTH = 'day_of_month';
    public const MONTH = 'month';
    public const DAY_OF_WEEK = 'day_of_week';
    public const NUMBER_OF_PROCESSES = 'number_of_processes';
    public const AUTO_START = 'auto_start';
    public const AUTO_RESTART = 'auto_restart';
    public const PREFIX = 'prefix';
    public const USER = 'user';
    public const CRONJOB = 'cronjob';
    public const WORKER = 'worker';
    public const SETTINGS = 'settings';
    public const DESTINATION_DIR = '%kernel.project_dir%/generated_conf/';

    private const NAME = 'name';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('drjele_symfony_console');

        $treeBuilder->getRootNode()
            ->children()
            ->append($this->buildCronjob())
            ->append($this->buildWorker());

        return $treeBuilder;
    }

    private function buildCronjob(): NodeDefinition
    {
        $cronjobTree = (new TreeBuilder(static::CRONJOB))->getRootNode()
            ->addDefaultsIfNotSet();

        $configTree = $cronjobTree->children()->arrayNode(static::CONFIG)
            ->addDefaultsIfNotSet();

        $configTree->children()
            ->scalarNode(static::TEMPLATE_CLASS)->defaultValue(CrontabTemplate::class)->end()
            ->scalarNode(static::CONF_FILES_DIR)->defaultValue(static::DESTINATION_DIR . 'cron')->end()
            ->scalarNode(static::LOGS_DIR)->defaultValue('%kernel.logs_dir%/cron')->end();

        $configTree->children()->arrayNode(static::SETTINGS)
            ->ignoreExtraKeys(false)
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode(static::LOG)->defaultTrue()->end()
            ->scalarNode(static::DESTINATION_FILE)->defaultValue('crontab')->end()
            ->booleanNode(static::HEARTBEAT)->defaultTrue()->end();

        /** @var NodeBuilder|ArrayNodeDefinition $commandsTree */
        $commandsTree = $cronjobTree->children()->arrayNode(static::COMMANDS)
            ->isRequired()
            ->useAttributeAsKey(static::NAME)
            ->prototype('array');

        $commandsTree->children()
            ->scalarNode(static::NAME)->end()
            ->arrayNode(static::COMMAND)/* command start */
            ->isRequired()
            ->beforeNormalization()->ifString()->then(fn($command) => [$command])->end()
            ->scalarPrototype()->end()
            ->end()/* command end */
            ->arrayNode(static::SCHEDULE)/* schedule start */
            ->children()
            ->scalarNode(static::MINUTE)->defaultValue('*')->end()
            ->scalarNode(static::HOUR)->defaultValue('*')->end()
            ->scalarNode(static::DAY_OF_MONTH)->defaultValue('*')->end()
            ->scalarNode(static::MONTH)->defaultValue('*')->end()
            ->scalarNode(static::DAY_OF_WEEK)->defaultValue('*')->end()
            ->end()
            ->end(); /* schedule end */

        $commandsTree->children()->arrayNode(static::SETTINGS)
            ->ignoreExtraKeys(false)
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode(static::LOG)->defaultNull()->end();

        return $cronjobTree;
    }

    private function buildWorker(): NodeDefinition
    {
        $workerTree = (new TreeBuilder(static::WORKER))->getRootNode()
            ->addDefaultsIfNotSet();

        $configTree = $workerTree->children()->arrayNode(static::CONFIG)
            ->addDefaultsIfNotSet();

        $configTree->children()
            ->scalarNode(static::TEMPLATE_CLASS)->defaultValue(SupervisorTemplate::class)->end()
            ->scalarNode(static::CONF_FILES_DIR)->defaultValue(static::DESTINATION_DIR . 'worker')->end()
            ->scalarNode(static::LOGS_DIR)->defaultValue('%kernel.logs_dir%/worker')->end();

        $settingsTree = $configTree->children()->arrayNode(static::SETTINGS)
            ->ignoreExtraKeys(false)
            ->addDefaultsIfNotSet();
        $this->appendSupervisorConfig($settingsTree->children());

        /** @var NodeBuilder|ArrayNodeDefinition $commandsTree */
        $commandsTree = $workerTree->children()->arrayNode(static::COMMANDS)
            ->isRequired()
            ->useAttributeAsKey(static::NAME)
            ->prototype('array');

        $commandsTree->children()
            ->scalarNode(static::NAME)->end()
            ->arrayNode(static::COMMAND)
            ->isRequired()
            ->beforeNormalization()->ifString()->then(fn($command) => [$command])->end()
            ->scalarPrototype()->end()
            ->end();

        $settingsTree = $commandsTree->children()->arrayNode(static::SETTINGS)
            ->ignoreExtraKeys(false)
            ->addDefaultsIfNotSet();
        $this->appendSupervisorConfig($settingsTree->children());

        return $workerTree;
    }

    private function appendSupervisorConfig(NodeBuilder $node): void
    {
        $node->integerNode(static::NUMBER_OF_PROCESSES)->defaultValue(1)->end()
            ->booleanNode(static::AUTO_START)->defaultTrue()->end()
            ->booleanNode(static::AUTO_RESTART)->defaultTrue()->end()
            ->scalarNode(static::PREFIX)->defaultNull()->end()
            ->scalarNode(static::USER)->defaultNull()->end();
    }
}
