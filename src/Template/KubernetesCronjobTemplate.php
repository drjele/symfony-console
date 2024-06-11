<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Template;

use Drjele\Symfony\Console\Contract\ConfigInterface;
use Drjele\Symfony\Console\Contract\TemplateInterface;
use Drjele\Symfony\Console\Dto\ConfFilesDto;
use Drjele\Symfony\Console\Dto\Cronjob\CommandDto;
use Drjele\Symfony\Console\Dto\Cronjob\ConfigDto;
use Drjele\Symfony\Console\Dto\Cronjob\ScheduleDto;
use Drjele\Symfony\Console\Template\Trait\KubernetesJobTrait;

class KubernetesCronjobTemplate implements TemplateInterface
{
    use KubernetesJobTrait;

    /** @param ConfigDto $configDto */
    public function generate(
        ConfigInterface $configDto,
        array $commands
    ): ConfFilesDto {
        $cronjobs = [];
        $index = 0;

        foreach ($commands as $commandDto) {
            $cronjobs['"' . ($index++) . '"'] = $this->buildCommand($commandDto, $configDto);
        }

        $content = $this->convertArrayToString(
            [
                'CronJobs' => [
                    'jobs' => $cronjobs,
                ],
            ]
        );

        /* crontab files need to end with an empty line */
        $content .= \PHP_EOL;

        $crontabPath = $configDto->getConfFilesDir() . '/' . $configDto->getSettings()->getDestinationFile();

        $confFilesDto = new ConfFilesDto();

        if (\count($cronjobs) > 0) {
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
            'command' => \implode(' ', $commandDto->getCommand()),
            'schedule' => '"' . $this->buildSchedule($commandDto->getSchedule()) . '"',
        ];
    }

    protected function buildSchedule(ScheduleDto $schedule): string
    {
        return \implode(
            ' ',
            [
                $schedule->getMinute(),
                $schedule->getHour(),
                $schedule->getDayOfMonth(),
                $schedule->getMonth(),
                $schedule->getDayOfWeek(),
            ]
        );
    }
}
