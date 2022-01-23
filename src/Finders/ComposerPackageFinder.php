<?php

declare(strict_types=1);

namespace Zvive\Fixer\Finders;

use PhpCsFixer\Finder;

class ComposerPackageFinder extends BaseFinder
{
    public static function configTypes() : iterable
    { return ['package']; }

    /**
     * Creates a Finder class preconfigured for Composer packages.
     */
    public static function create(string $baseDir) : Finder
    {
        return BasicProjectFinder::create($baseDir)
            ->in(static::onlyExistingPaths([
                "{$baseDir}/src",
                "{$baseDir}/tests",
            ]))
        ;
    }
}
