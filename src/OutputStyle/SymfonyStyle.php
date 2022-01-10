<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\OutputStyle;

use Drjele\Symfony\Console\OutputStyle\Traits\SymfonyStyleTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle as BaseSymfonyStyle;

class SymfonyStyle
{
    use SymfonyStyleTrait;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->style = new BaseSymfonyStyle($input, $output);
    }
}
