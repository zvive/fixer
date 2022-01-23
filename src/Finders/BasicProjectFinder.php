<?php

declare(strict_types=1);

namespace Zvive\Fixer\Finders;

use PhpCsFixer\Finder;

class BasicProjectFinder extends BaseFinder
{
//    public static function configTypes() => {
//      return static fn() => ['project']);
//    }
    public static function configTypes() :iterable {
      return ['project'];
    }

    /**
     * Creates a Finder class preconfigured for standard composer-based projects.
     */
    public static function create(string $baseDir) : Finder
    {
        return Finder::create()
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->name('*.php')
            ->exclude([
                "{$baseDir}/vendor",
            ])
        ;
    }
}
