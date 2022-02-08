<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Contract;

interface SettingsInterface
{
    public function getSettings(): SettingInterface;
}
