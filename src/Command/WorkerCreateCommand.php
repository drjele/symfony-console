<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Command;

use Drjele\Symfony\Console\Dto\Worker\WorkerDto;
use Drjele\Symfony\Console\Service\ConfGenerateService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class WorkerCreateCommand extends AbstractCommand
{
    public const NAME = 'drjele:symfony:console:worker-create';

    private ConfGenerateService $confGenerateService;
    private ?WorkerDto $workerDto;

    public function __construct(
        ConfGenerateService $confGenerateService,
        ?array $config
    ) {
        $this->confGenerateService = $confGenerateService;
        $this->workerDto = null === $config ? null : new WorkerDto($config);

        parent::__construct(static::NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (null === $this->workerDto) {
            $this->warning('no configuration is set');

            return static::SUCCESS;
        }

        try {
            $confFiles = $this->confGenerateService->generate(
                $this->workerDto->getConfig(),
                $this->workerDto->getCommands()
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
