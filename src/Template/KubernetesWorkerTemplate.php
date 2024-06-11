<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Template;

use Drjele\Symfony\Console\Contract\ConfigInterface;
use Drjele\Symfony\Console\Contract\TemplateInterface;
use Drjele\Symfony\Console\Dto\ConfFilesDto;
use Drjele\Symfony\Console\Dto\Worker\CommandDto;
use Drjele\Symfony\Console\Dto\Worker\ConfigDto;
use Drjele\Symfony\Console\Template\Trait\KubernetesJobTrait;
use Drjele\Symfony\Console\Template\Trait\WorkerNumberOfProcessesTrait;

class KubernetesWorkerTemplate implements TemplateInterface
{
    use KubernetesJobTrait;
    use WorkerNumberOfProcessesTrait;

    /** @param ConfigDto $configDto */
    public function generate(
        ConfigInterface $configDto,
        array $commands
    ): ConfFilesDto {
        $workers = [];
        $index = 0;

        foreach ($commands as $commandDto) {
            $workers['"' . ($index++) . '"'] = $this->buildCommand($commandDto, $configDto);
        }

        $content = $this->convertArrayToString(
            [
                'Jobs' => [
                    'workers' => $workers,
                ],
            ]
        );

        /* crontab files need to end with an empty line */
        $content .= \PHP_EOL;

        $crontabPath = $configDto->getConfFilesDir() . '/' . $configDto->getSettings()->getSetting('destinationFile');

        $confFilesDto = new ConfFilesDto();

        if (\count($workers) > 0) {
            $confFilesDto->addFile($crontabPath, $content);
        }

        return $confFilesDto;
    }

    protected function buildCommand(
        CommandDto $commandDto,
        ConfigDto $configDto
    ): array {
        $name = $this->sanitize($commandDto->getName());

        return [
            'name' => $name,
            'command' => '"' . \implode(' ', $commandDto->getCommand()) . '"',
            'parallelism' => $this->getNumberOfProcesses($configDto, $commandDto),
        ];
    }
}
