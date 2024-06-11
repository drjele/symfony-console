<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Worker;

use Drjele\Symfony\Console\Contract\ConfigInterface;
use Drjele\Symfony\Console\Contract\SettingsInterface;
use Drjele\Symfony\Console\DependencyInjection\Configuration;
use Drjele\Symfony\Console\Dto\Trait\ConfigTrait;

class ConfigDto implements ConfigInterface, SettingsInterface
{
    use ConfigTrait;

    private ConfigSettingsDto $settings;

    public function __construct(array $config)
    {
        $this->setConfigs($config);

        $this->settings = new ConfigSettingsDto($config[Configuration::SETTINGS]);
    }

    public function getSettings(): ConfigSettingsDto
    {
        return $this->settings;
    }
}
