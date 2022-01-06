<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Cronjob;

use Drjele\Symfony\Console\Dto\Traits\SettingsTrait;

class ConfigSettingsDto
{
    use SettingsTrait;

    private bool $log;
    private string $destinationFile;
    private bool $heartbeat;

    public function __construct(array $settings)
    {
        $this->loadProperties($settings);
    }

    public function getLog(): bool
    {
        return $this->log;
    }

    public function getDestinationFile(): string
    {
        return $this->destinationFile;
    }

    public function getHeartbeat(): bool
    {
        return $this->heartbeat;
    }
}
