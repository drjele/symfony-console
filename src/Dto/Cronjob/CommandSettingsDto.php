<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Cronjob;

use Drjele\Symfony\Console\Contract\SettingInterface;
use Drjele\Symfony\Console\Dto\Traits\SettingsTrait;

class CommandSettingsDto implements SettingInterface
{
    use SettingsTrait;

    private ?bool $log;

    public function __construct(array $settings)
    {
        $this->log = null;

        $this->loadProperties($settings);
    }

    public function getLog(): ?bool
    {
        return $this->log;
    }
}
