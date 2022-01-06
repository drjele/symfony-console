<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Command;

use DateTime;
use Drjele\Symfony\Console\Command\Traits\OutputTrait;
use Drjele\Symfony\Console\Service\MemoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class AbstractCommand extends Command
{
    use OutputTrait;

    /** there are here for older symfony projects */
    public const SUCCESS = 0;
    public const FAILURE = 1;
    public const INVALID = 2;

    protected const MEMORY_LIMIT = 'memory-limit';
    protected const TIME_LIMIT = 'time-limit';

    protected InputInterface $input;
    protected OutputInterface $output;
    protected ?string $memoryLimit;
    protected ?int $timeLimit;

    private int $startTime;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->startTime = \time();

        $this->input = $input;
        $this->output = $output;
        $this->memoryLimit = null;
        $this->timeLimit = null;

        $this->io = new SymfonyStyle($input, $output);

        if ($this->input->hasOption(self::MEMORY_LIMIT)
            && $memoryLimit = $this->input->getOption(self::MEMORY_LIMIT)) {
            $this->memoryLimit = (string)$memoryLimit;

            MemoryService::setMemoryLimitIfNotHigher($this->memoryLimit);
        }

        if ($this->input->hasOption(self::TIME_LIMIT)
            && $timeLimit = $this->input->getOption(self::TIME_LIMIT)) {
            $this->timeLimit = (int)$timeLimit;
        }

        $this->io->title(\sprintf('<bg=blue>[%s]</> %s', (new DateTime())->format('Y-m-d'), $this->getName()));
    }

    protected function configure(): void
    {
        parent::configure();

        $this->addOption(
            self::MEMORY_LIMIT,
            null,
            InputOption::VALUE_OPTIONAL,
            'max memory allowed to be used before the command automatically stops',
            '512M'
        )
            ->addOption(
                self::TIME_LIMIT,
                null,
                InputOption::VALUE_OPTIONAL,
                'max runtime in seconds that the command is allowed to run for',
                600
            );
    }

    protected function stopScriptIfLimitsReached(): void
    {
        if ($this->didScriptReachedLimits()) {
            exit(static::INVALID);
        }
    }

    protected function didScriptReachedLimits(): bool
    {
        return $this->isMemoryLimitReached()
            || $this->isTimeLimitReached();
    }

    private function isTimeLimitReached(): bool
    {
        if (null === $this->timeLimit) {
            return false;
        }

        $timeUsed = \time() - $this->startTime;

        if ($timeUsed >= $this->timeLimit) {
            $this->warning(
                \sprintf('max run time reached `%s`/`%s` seconds', $timeUsed, $this->timeLimit)
            );

            return true;
        }

        return false;
    }

    private function isMemoryLimitReached(): bool
    {
        if (null === $this->memoryLimit) {
            return false;
        }

        $memoryLimit = MemoryService::returnBytes($this->memoryLimit);

        $memoryUsage = \memory_get_usage(true);

        if ($memoryUsage > $memoryLimit - 10485760) {
            $humanReadableMemoryUsed = MemoryService::convertBitsToHumanReadable($memoryUsage);
            $humanReadableMemoryLimit = MemoryService::convertBitsToHumanReadable($memoryLimit - 10485760);

            $this->warning(
                \sprintf(
                    'max allowed memory usage reached `%s`/`%s`',
                    $humanReadableMemoryUsed,
                    $humanReadableMemoryLimit
                )
            );

            return true;
        }

        return false;
    }
}
