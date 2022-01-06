<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Exception;

class SettingNotFound extends Exception
{
    public function __construct(string $setting, string $class)
    {
        $message = \sprintf('the setting `%s` is not set for `%s`', $setting, $class);

        parent::__construct($message);
    }
}
