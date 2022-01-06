<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto\Traits;

use Drjele\Symfony\Console\Exception\SettingNotFound;
use stdClass;

trait SettingsTrait
{
    private stdClass $settings;

    public function getSetting(string $setting): ?string
    {
        if (!\property_exists($this->settings, $setting)) {
            throw new SettingNotFound($setting, static::class);
        }

        $value = $this->settings->{$setting};

        return null !== $value ? (string)$value : $value;
    }

    private function loadProperties(array $data): void
    {
        $this->settings = new stdClass();

        foreach ($data as $key => $value) {
            $propertyName = $this->toCamelCase($key);

            if (\property_exists($this, $propertyName)) {
                $this->{$propertyName} = $value;
                continue;
            }

            $this->settings->{$propertyName} = $value;
        }
    }

    private function toCamelCase(string $string): string
    {
        $string = \str_replace(' ', '', \ucwords(\str_replace('_', ' ', $string)));

        return \lcfirst($string);
    }
}
