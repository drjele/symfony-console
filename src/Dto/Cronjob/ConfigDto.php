<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Cronjob;

use Drjele\Symfony\Console\Contract\ConfigInterface;
use Drjele\Symfony\Console\DependencyInjection\Configuration;
use Drjele\Symfony\Console\Dto\Traits\ConfigTrait;

class ConfigDto implements ConfigInterface
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
