<?php

namespace Zvive\Fixer\Tests\Unit\Commands\Support;

use Zvive\Fixer\Commands\Support\ConfigGenerator;
use PHPUnit\Framework\TestCase;

class ConfigGeneratorTest extends TestCase
{


    /** @test */
    public function it_calls_set_paths_from_the_constructor_using_provided_arguments(): void
    {
        $generator = new ConfigGenerator(['aaa'], ['bbb']);

        static::assertSame(['aaa'], $generator->includePaths);
        static::assertSame(['bbb'], $generator->excludePaths);
    }

    /** @test */
    public function it_generates_finder_code_with_included_paths_correctly(): void
    {
        $generator = new ConfigGenerator(['aaa', 'ccc'], ['bbb']);
        $code = $generator->generateFinderCode();

        $pattern = PHP_EOL;
        static::assertMatchesRegularExpression("~->in\(\[$pattern\s*__DIR__ . '/aaa',$pattern\s*__DIR__ . '/ccc',$pattern\s*\]\)~s", $code);
        static::assertStringContainsString("__DIR__ . '/aaa'," . PHP_EOL, $code);
        static::assertStringContainsString("__DIR__ . '/ccc'," . PHP_EOL, $code);
    }

    /** @test */
    public function it_generates_finder_code_with_excluded_paths_correctly(): void
    {
        $generator = new ConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateCustomFinderCode();

        static::assertStringContainsString("->exclude(['bbb'])" . PHP_EOL, $code);

        $generator = new ConfigGenerator(['aaa'], []);
        $code = $generator->generateCustomFinderCode();

        static::assertStringNotContainsString('->exclude(', $code);
    }

    /** @test */
    public function it_generates_finder_code_with_the_vendor_path_excluded(): void
    {
        $generator = new ConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateCustomFinderCode();

        static::assertStringContainsString("->exclude(__DIR__ . '/vendor')", $code);
    }

    /** @test */
    public function it_generates_finder_code_that_includes_php_files(): void
    {
        $generator = new ConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateFinderCode();

        static::assertStringContainsString("->name('*.php')", $code);
    }

    /** @test */
    public function it_generates_finder_code_that_excludes_vcs_and_dotfiles(): void
    {
        $generator = new ConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateCustomFinderCode();

        static::assertStringContainsString('->ignoreVCS(true)', $code);
        static::assertStringContainsString('->ignoreDotFiles(true)', $code);
    }

    /** @test */
    public function it_generates_finder_code_that_creates_a_finder_class_instance(): void
    {
        $generator = new ConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateFinderCode();

        static::assertStringContainsString('Finder::create()', $code);
    }
}
