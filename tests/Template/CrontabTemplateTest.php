<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Test\Template;

use Drjele\Symfony\Console\DependencyInjection\Configuration;
use Drjele\Symfony\Console\Dto\Cronjob\CommandDto;
use Drjele\Symfony\Console\Dto\Cronjob\ConfigDto;
use Drjele\Symfony\Console\Template\CrontabTemplate;
use Drjele\Symfony\Phpunit\MockDto;
use Drjele\Symfony\Phpunit\TestCase\AbstractTestCase;
use Mockery\MockInterface;

/**
 * @internal
 */
final class CrontabTemplateTest extends AbstractTestCase
{
    public static function getMockDto(): MockDto
    {
        return new MockDto(CrontabTemplate::class, [], true);
    }

    public function test(): void
    {
        /** @var CrontabTemplate|MockInterface $mock */
        $mock = $this->get(CrontabTemplate::class);

        $config = new ConfigDto(
            [
                Configuration::TEMPLATE_CLASS => 'test',
                Configuration::CONF_FILES_DIR => 'test',
                Configuration::LOGS_DIR => 'test',
                Configuration::SETTINGS => [
                    Configuration::DESTINATION_FILE => 'test',
                    Configuration::HEARTBEAT => true,
                ],
            ]
        );
        $commands = [
            new CommandDto(
                'test',
                [
                    Configuration::COMMAND => ['test'],
                    Configuration::SCHEDULE => [
                        Configuration::MINUTE => '*',
                        Configuration::HOUR => '*',
                        Configuration::DAY_OF_MONTH => '*',
                        Configuration::MONTH => '*',
                        Configuration::DAY_OF_WEEK => '*',
                    ],
                    Configuration::SETTINGS => [
                        Configuration::LOG => true,
                    ],
                ]
            ),
        ];

        $confFilesDto = $mock->generate($config, $commands);

        static::assertCount(1, $confFilesDto->getFiles());
    }
}
