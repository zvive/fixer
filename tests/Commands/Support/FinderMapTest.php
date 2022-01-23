<?php

namespace Zvive\Fixer\Tests\Unit\Commands\Support;

use Zvive\Fixer\Commands\Support\FinderMap;
use Zvive\Fixer\Finders\BasicProjectFinder;
use Zvive\Fixer\Finders\ComposerPackageFinder;
use Zvive\Fixer\Finders\LaravelPackageFinder;
use PHPUnit\Framework\TestCase;

class FinderMapTest extends TestCase
{
    /** @test */
    public function it_maps_config_types_to_classnames(): void
    {
        $map = new FinderMap([ComposerPackageFinder::class]);

        foreach (ComposerPackageFinder::configTypes() as $type) {
            static::assertArrayHasKey($type, $map->getMap());
        }

        $expectedMap = [];

        foreach (ComposerPackageFinder::configTypes() as $type) {
            $expectedMap[$type] = ComposerPackageFinder::class;
        }

        static::assertEquals($expectedMap, $map->getMap());
    }

    /** @test */
    public function it_finds_config_types_and_returns_the_correct_classname(): void
    {
        $map = new FinderMap([ComposerPackageFinder::class, LaravelPackageFinder::class]);

        foreach (ComposerPackageFinder::configTypes() as $type) {
            static::assertEquals(ComposerPackageFinder::class, $map->find($type));
        }

        foreach (LaravelPackageFinder::configTypes() as $type) {
            static::assertEquals(LaravelPackageFinder::class, $map->find($type));
        }
    }

    /** @test */
    public function it_returns_the_basic_project_finder_classname_when_find_fails(): void
    {
        $map = new FinderMap([ComposerPackageFinder::class]);

        static::assertEquals(BasicProjectFinder::class, $map->find('test'));
    }

    /** @test */
    public function it_maps_a_type_to_any_string_value(): void
    {
        $map = new FinderMap([]);

        $map->mapType('one', 'ONE');
        $map->mapType('two', 'two');

        static::assertArrayHasKey('one', $map->getMap());
        static::assertArrayHasKey('two', $map->getMap());

        static::assertSame('ONE', $map->getMap()['one']);
        static::assertSame('two', $map->getMap()['two']);
    }

    /** @test */
    public function it_returns_an_array_when_get_map_is_called(): void
    {
        $map = new FinderMap([]);

        static::assertIsArray($map->getMap());
    }
}
