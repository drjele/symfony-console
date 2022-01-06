<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Worker;

use Drjele\Symfony\Console\Dto\Traits\SettingsTrait;
use Drjele\Symfony\Console\Dto\Traits\SupervisorSettingsTrait;

class ConfigSettingsDto
{
    use SettingsTrait;
    use SupervisorSettingsTrait;

    public function __construct(array $settings)
    {
        $this->initSupervisorSettings();

        $this->loadProperties($settings);
    }
}
