<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Command\Traits;

use DateTime;
use Drjele\Symfony\Console\Service\MemoryService;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

trait OutputTrait
{
    protected SymfonyStyle $io;

    protected function writeln(string $text): void
    {
        $this->io->writeln($this->format($text));
    }

    protected function error(string $text, Throwable $t = null): void
    {
        if (null !== $t) {
            $text = \sprintf('%s / %s::%s / %s', $text, $t->getFile(), $t->getLine(), $t->getTraceAsString());
        }

        $this->io->error($this->format($text));
    }

    protected function warning(string $text): void
    {
        $this->io->warning($this->format($text));
    }

    protected function success(string $text): void
    {
        $this->io->success($this->format($text));
    }

    private function format(string $text): string
    {
        return \sprintf(
            '[%s][%s] %s',
            (new DateTime())->format('H:i:s'),
            MemoryService::getMemoryUsage(),
            $text
        );
    }
}
