<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Command;

use DateTime;
use Drjele\Symfony\Console\Command\Trait\MemoryAndTimeLimitsTrait;
use Drjele\Symfony\Console\OutputStyle\Trait\SymfonyStyleTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    use SymfonyStyleTrait;

    /** @deprecated will be removed from here in next minor version */
    use MemoryAndTimeLimitsTrait;

    /** there are here for older symfony projects */
    public const SUCCESS = 0;
    public const FAILURE = 1;
    public const INVALID = 2;

    protected InputInterface $input;
    protected OutputInterface $output;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;

        $this->initializeSymfonyStyle();
        $this->initializeMemoryAndTimeLimits();

        $this->style->title(\sprintf('<bg=blue>[%s]</> %s', (new DateTime())->format('Y-m-d'), $this->getName()));
    }

    protected function configure(): void
    {
        parent::configure();

        $this->configureMemoryAndTimeLimits();
    }
}
