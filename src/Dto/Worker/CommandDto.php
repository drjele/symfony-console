<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Worker;

use Drjele\Symfony\Console\DependencyInjection\Configuration;

class CommandDto
{
    private string $name;
    private array $command;
    private CommandSettingsDto $settings;

    public function __construct(
        string $name,
        array $parameters
    ) {
        $this->name = $name;
        $this->command = $parameters[Configuration::COMMAND];
        $this->settings = new CommandSettingsDto($parameters[Configuration::SETTINGS] ?? []);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCommand(): ?array
    {
        return $this->command;
    }

    public function getSettings(): CommandSettingsDto
    {
        return $this->settings;
    }
}
