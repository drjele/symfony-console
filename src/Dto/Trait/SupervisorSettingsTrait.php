<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Trait;

trait SupervisorSettingsTrait
{
    private ?int $numberOfProcesses = null;
    private ?bool $autoStart = null;
    private ?bool $autoRestart = null;
    private ?string $prefix = null;
    private ?string $user = null;

    public function getNumberOfProcesses(): ?int
    {
        return $this->numberOfProcesses;
    }

    public function getAutoStart(): ?bool
    {
        return $this->autoStart;
    }

    public function getAutoRestart(): ?bool
    {
        return $this->autoRestart;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    private function initSupervisorSettings(): void
    {
        $this->numberOfProcesses = null;
        $this->autoStart = null;
        $this->autoRestart = null;
        $this->prefix = null;
        $this->user = null;
    }
}
