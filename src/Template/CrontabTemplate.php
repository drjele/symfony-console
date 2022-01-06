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

class CrontabTemplate implements TemplateInterface
{
    /** @param ConfigDto $configDto */
    public function generate(ConfigInterface $configDto, array $commands): ConfFilesDto
    {
        $cronjobs = [];

        foreach ($commands as $commandDto) {
            $cronjobs[] = $this->buildCommand($commandDto, $configDto);
        }

        $content = \str_replace(
            [
                '%commands%',
            ],
            [
                \implode(\PHP_EOL . \PHP_EOL, $cronjobs),
            ],
            $this->getTemplate()
        );

        /* crontab files need to end with an empty line */
        $content .= \PHP_EOL;

        $crontabPath = $configDto->getConfFilesDir() . '/' . $configDto->getSettings()->getDestinationFile();

        $confFilesDto = new ConfFilesDto();

        $confFilesDto->addFile($crontabPath, $content);

        return $confFilesDto;
    }

    protected function buildCommand(CommandDto $commandDto, ConfigDto $configDto): string
    {
        $schedule = $this->buildSchedule($commandDto->getSchedule());

        $commandParts = $commandDto->getCommand();
        \array_unshift($commandParts, $schedule);

        if ($commandDto->getSettings()->getLog() ?? $configDto->getSettings()->getLog()) {
            $commandParts[] = \sprintf(
                '>> %s/%s.log 2>&1',
                $configDto->getLogsDir(),
                $commandDto->getName()
            );
        }

        return \implode(' ', $commandParts);
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

    protected function getTemplate(): string
    {
        return '#############################################################################
#
# GENERATED FILE
# DO NOT EDIT LOCALLY
#
#############################################################################
%commands%
#############################################################################';
    }
}
