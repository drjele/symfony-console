<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Contract;

interface ConfigInterface
{
    public function getTemplateClass(): string;

    public function getLogsDir(): string;

    public function getConfFilesDir(): string;
}
