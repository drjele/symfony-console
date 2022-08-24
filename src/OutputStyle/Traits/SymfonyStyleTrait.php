<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\OutputStyle\Traits;

use DateTime;
use Drjele\Symfony\Console\Service\MemoryService;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

trait SymfonyStyleTrait
{
    protected SymfonyStyle $style;

    public function writeln(string $text): void
    {
        $this->style->writeln($this->format($text));
    }

    public function error(string $text, Throwable $t = null, bool $exposeTrace = false): void
    {
        if (null !== $t) {
            $text = \sprintf('%s / %s::%s::%s', $text, $t::class, $t->getFile(), $t->getLine());

            if (true === $exposeTrace) {
                $text = \sprintf('%s / %s', $text, $t->getTraceAsString());
            }
        }

        $this->style->error($this->format($text));
    }

    public function warning(string $text): void
    {
        $this->style->warning($this->format($text));
    }

    public function success(string $text): void
    {
        $this->style->success($this->format($text));
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
