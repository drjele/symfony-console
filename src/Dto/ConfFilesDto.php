<?php

declare(strict_types=1);

/*
 * Copyright (c) Adrian Jeledintan
 */

namespace Drjele\Symfony\Console\Dto;

use Drjele\Symfony\Console\Exception\Exception;

class ConfFilesDto
{
    private array $files;

    public function __construct()
    {
        $this->files = [];
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function addFile(string $path, string $content): self
    {
        if (true === isset($this->files[$path])) {
            throw new Exception(
                \sprintf('the file path is in use `%s`', $path)
            );
        }

        $this->files[$path] = $content;

        return $this;
    }
}
