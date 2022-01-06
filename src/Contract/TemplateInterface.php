<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Contract;

use Drjele\Symfony\Console\Dto\ConfFilesDto;

interface TemplateInterface
{
    public function generate(ConfigInterface $configDto, array $commands): ConfFilesDto;
}
