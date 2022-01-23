<?php

declare(strict_types=1);

namespace Zvive\Fixer\Finders;

use PhpCsFixer\Finder;
use Illuminate\Support\Collection;

abstract class BaseFinder implements Contracts\FinderInterface
{
    abstract public static function configTypes() : iterable;

    abstract public static function create(string $baseDir): Finder;

    public static function onlyExistingPaths(iterable $paths = []) : iterable
    {
        return array_filter($paths, static fn($path) => file_exists($path));
    }
}
