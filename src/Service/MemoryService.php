<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Service;

class MemoryService
{
    public static function setMemoryLimitIfNotHigher(string $newLimit): void
    {
        if (static::returnBytes(\ini_get('memory_limit')) < static::returnBytes($newLimit)) {
            \ini_set('memory_limit', $newLimit);
        }
    }

    public static function getMemoryUsage(): string
    {
        $bytes = \memory_get_usage(true);

        return static::convertBytesToHumanReadable($bytes);
    }

    public static function convertBytesToHumanReadable(int $bytes): string
    {
        $unit = ['B ', 'KB', 'MB', 'GB', 'TB', 'PB'];

        return @\round($bytes / 1024 ** ($i = \floor(\log($bytes, 1024))), 2) . ' ' . $unit[(int)$i];
    }

    public static function returnBytes(string $value): int
    {
        $value = \trim($value);

        \preg_match('#([0-9]+)[\s]*([a-z]+)#i', $value, $matches);

        $value = (int)($matches[1] ?? $value);
        $unitOfMeasurement = $matches[2] ?? null;

        if (null !== $unitOfMeasurement) {
            switch (\strtolower($unitOfMeasurement)) {
                case 'g':
                case 'gb':
                    $value *= 1024 * 1024 * 1024;
                    break;
                case 'm':
                case 'mb':
                    $value *= 1024 * 1024;
                    break;
                case 'k':
                case 'kb':
                    $value *= 1024;
                    break;
            }
        }

        return $value;
    }
}
