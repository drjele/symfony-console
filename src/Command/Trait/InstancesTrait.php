<?php

declare(strict_types=1);

namespace Drjele\Symfony\Console\Command\Trait;

use Drjele\Symfony\Console\Exception\Exception;
use Symfony\Component\Console\Input\InputOption;

trait InstancesTrait
{
    protected const MAX_INSTANCES = 'max-instances';
    protected const INSTANCE_INDEX = 'instance-index';

    protected function computeInstances(): array
    {
        $instances = (int)$this->input->getoption(self::MAX_INSTANCES);
        $instanceIndex = (int)$this->input->getoption(self::INSTANCE_INDEX);

        if ($instances < 1 || $instanceIndex < 1 || $instanceIndex > $instances) {
            throw new Exception('invalid instances and instance index provided');
        }

        return [$instances, $instanceIndex];
    }

    private function configureInstances(): void
    {
        $this->addOption(self::MAX_INSTANCES, null, InputOption::VALUE_OPTIONAL, 'the number of instances of this command', 1)
            ->addOption(self::INSTANCE_INDEX, null, InputOption::VALUE_OPTIONAL, 'the index of the current command instance up to the max instances', 1);
    }
}