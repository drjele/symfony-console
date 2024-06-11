<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Worker;

use Drjele\Symfony\Console\Contract\SettingInterface;
use Drjele\Symfony\Console\Dto\Trait\SettingsTrait;
use Drjele\Symfony\Console\Dto\Trait\SupervisorSettingsTrait;

class CommandSettingsDto implements SettingInterface
{
    use SettingsTrait;
    use SupervisorSettingsTrait;

    public function __construct(array $settings)
    {
        $this->initSupervisorSettings();

        $this->loadProperties($settings);
    }
}
