<?php

namespace Zvive\Fixer\Tests\Unit\Finders;

use Zvive\Fixer\Finders\ComposerPackageFinder;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class ComposerPackageFinderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_php_cs_finder_object(): void
    {
        $finder = ComposerPackageFinder::create(__DIR__ . '/../..');

        static::assertInstanceOf(Finder::class, $finder);
    }

    /** @test */
    public function it_returns_package_as_one_of_the_config_types(): void
    {
        static::assertContains('package', ComposerPackageFinder::configTypes());
    }
}
