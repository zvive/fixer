<?php declare(strict_types=1);

namespace Zvive\Fixer\Finders;

use PhpCsFixer\Finder;

class LaravelPackageFinder extends BaseFinder
{
    public static function configTypes() : iterable
    {
        return ['laravel:package'];
    }

    /**
     * Creates a Finder class preconfigured for Laravel packages.
     */
    public static function create(string $baseDir) : Finder
    {
        return BasicProjectFinder::create($baseDir)
//            ->name('*.php-cs-fixer.php')
            ->notName('*.blade.php')
            ->exclude([
                "{$baseDir}/resources",
            ])
            ->in(static::onlyExistingPaths([
                "{$baseDir}/config",
                "{$baseDir}/src",
                "{$baseDir}/tests",
                "{$baseDir}/stubs",
                "{$baseDir}/database",
                "{$baseDir}/routes",
            ]))
        ;
    }
}
