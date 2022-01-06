<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Cronjob;

use Drjele\Symfony\Console\DependencyInjection\Configuration;

class ScheduleDto
{
    private string $minute;
    private string $hour;
    private string $dayOfMonth;
    private string $month;
    private string $dayOfWeek;

    public function __construct(array $schedule)
    {
        $this->minute = $schedule[Configuration::MINUTE];
        $this->hour = $schedule[Configuration::HOUR];
        $this->dayOfMonth = $schedule[Configuration::DAY_OF_MONTH];
        $this->month = $schedule[Configuration::MONTH];
        $this->dayOfWeek = $schedule[Configuration::DAY_OF_WEEK];
    }

    public function getMinute(): string
    {
        return $this->minute;
    }

    public function getHour(): string
    {
        return $this->hour;
    }

    public function getDayOfMonth(): string
    {
        return $this->dayOfMonth;
    }

    public function getMonth(): string
    {
        return $this->month;
    }

    public function getDayOfWeek(): string
    {
        return $this->dayOfWeek;
    }
}
