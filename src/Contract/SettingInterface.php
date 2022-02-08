<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Contract;

interface SettingInterface
{
    public function getSetting(string $setting): ?string;
}
