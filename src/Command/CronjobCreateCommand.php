<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Command;

use Drjele\Symfony\Console\Dto\Cronjob\CronjobDto;
use Drjele\Symfony\Console\Service\ConfGenerateService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CronjobCreateCommand extends AbstractCommand
{
    public const NAME = 'drjele:symfony:console:cronjob-create';

    private ConfGenerateService $confGenerateService;
    private ?CronjobDto $cronjobDto;

    public function __construct(
        ConfGenerateService $confGenerateService,
        ?array $config
    ) {
        $this->confGenerateService = $confGenerateService;
        $this->cronjobDto = null === $config ? null : new CronjobDto($config);

        parent::__construct(static::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (null === $this->cronjobDto) {
            $this->warning('no configuration is set');

            return static::SUCCESS;
        }

        try {
            $confFiles = $this->confGenerateService->generate(
                $this->cronjobDto->getConfig(),
                $this->cronjobDto->getCommands()
            );

            $confFilesCount = \count($confFiles);

            if (0 === $confFilesCount) {
                $this->warning('no conf files were generated');
            } else {
                $this->success(\sprintf('generated `%s` conf files', $confFilesCount));

                foreach ($confFiles as $confFile) {
                    $this->writeln($confFile);
                }
            }
        } catch (Throwable $t) {
            $this->error($t->getMessage());

            return static::FAILURE;
        }

        return static::SUCCESS;
    }
}
