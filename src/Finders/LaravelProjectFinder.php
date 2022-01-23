<?php

declare(strict_types=1);

namespace Zvive\Fixer\Finders;

use PhpCsFixer\Finder;

class LaravelProjectFinder extends BaseFinder
{
    public static function configTypes() : iterable
    {
        return [
            'laravel',
            'laravel:project',
        ];
    }

    /**
     * Creates a Finder class preconfigured for Laravel projects.
     */
    public static function create(string $baseDir) : Finder
    {
        return BasicProjectFinder::create($baseDir)
            ->notName('*.blade.php')
            ->exclude([
                "{$baseDir}/bootstrap",
                "{$baseDir}/public",
                "{$baseDir}/resources",
                "{$baseDir}/storage",
            ])
            ->in(static::onlyExistingPaths([
                "{$baseDir}/app",
                "{$baseDir}/config",
                "{$baseDir}/database",
                "{$baseDir}/routes",
                "{$baseDir}/tests",
            ]))
        ;
    }
}