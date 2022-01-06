<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Template\Traits;

use Drjele\Symfony\Console\Dto\Worker\CommandDto;
use Drjele\Symfony\Console\Dto\Worker\ConfigDto;
use Drjele\Symfony\Console\Exception\Exception;

trait WorkerNumberOfProcessesTrait
{
    protected function getNumberOfProcesses(ConfigDto $configDto, CommandDto $commandDto): int
    {
        $numberOfProcesses = $commandDto->getSettings()->getNumberOfProcesses() ?? $configDto->getSettings()->getNumberOfProcesses();

        if (!$numberOfProcesses) {
            throw new Exception('invalid `number of processes`');
        }

        return $numberOfProcesses;
    }
}
