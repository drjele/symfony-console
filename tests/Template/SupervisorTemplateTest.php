<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Test\Template;

use Drjele\Symfony\Console\DependencyInjection\Configuration;
use Drjele\Symfony\Console\Dto\Worker\CommandDto;
use Drjele\Symfony\Console\Dto\Worker\ConfigDto;
use Drjele\Symfony\Console\Template\SupervisorTemplate;
use Drjele\Symfony\Phpunit\MockDto;
use Drjele\Symfony\Phpunit\TestCase\AbstractTestCase;
use Mockery\MockInterface;

/**
 * @internal
 */
final class SupervisorTemplateTest extends AbstractTestCase
{
    public static function getMockDto(): MockDto
    {
        return new MockDto(SupervisorTemplate::class, [], true);
    }

    public function test(): void
    {
        /** @var SupervisorTemplate|MockInterface $mock */
        $mock = $this->get(SupervisorTemplate::class);

        $config = new ConfigDto(
            [
                Configuration::TEMPLATE_CLASS => 'test',
                Configuration::CONF_FILES_DIR => 'test',
                Configuration::LOGS_DIR => 'test',
                Configuration::SETTINGS => [
                    Configuration::AUTO_START => true,
                    Configuration::AUTO_RESTART => true,
                ],
            ]
        );
        $commands = [
            new CommandDto(
                'test',
                [
                    Configuration::COMMAND => ['test'],
                    Configuration::SETTINGS => [
                        Configuration::PREFIX => 'test',
                        Configuration::USER => 'test',
                        Configuration::NUMBER_OF_PROCESSES => 1,
                    ],
                ]
            ),
        ];

        $confFilesDto = $mock->generate($config, $commands);

        static::assertCount(1, $confFilesDto->getFiles());
    }
}
