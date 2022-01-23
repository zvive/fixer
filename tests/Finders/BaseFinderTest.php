<?php

namespace Zvive\Fixer\Tests\Unit\Finders;

use Zvive\Fixer\Finders\BaseFinder;
use PHPUnit\Framework\TestCase;

class BaseFinderTest extends TestCase
{
    /**
     * @test
     */
    public function it_only_includes_existing_paths(): void
    {
        $testDirs = [
            __DIR__ . '/../../src',
            __DIR__ . '/../../tests',
            __DIR__ . '/../../missing-dir-1',
            __DIR__ . '/../../missing-dir-2',
        ];
        $existingPaths = BaseFinder::onlyExistingPaths($testDirs);

        static::assertCount(2, $existingPaths);
        static::assertEquals([
            __DIR__ . '/../../src',
            __DIR__ . '/../../tests',
        ], $existingPaths);
    }
}
