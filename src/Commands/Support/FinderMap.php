<?php

namespace Zvive\Fixer\Commands\Support;

use Zvive\Fixer\Finders\BasicProjectFinder;
use Illuminate\Support\Str;

class FinderMap
{


    /**
     * Maps the provided classnames to their config type names (::configTypes() is called on each class).
     *
     * @param array $finderClasses
     */
    public function __construct( protected array $finderClasses, protected array $map = [])
    {
        $this->mapTypesToClasses($finderClasses);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function find(string $name): string
    {
        return $this->map[Str::snake($name)] ?? BasicProjectFinder::class;
    }

    /**
     * Maps config type names to their associated Finder classnames.
     *
     * @param array $finderClassnames
     */
    protected function mapTypesToClasses(array $finderClassnames): void
    {
        $this->map = [];

        foreach ($finderClassnames as $finderClass) {
            foreach ($finderClass::configTypes() as $configType) {
                $this->mapType($configType, $finderClass);
            }
        }
    }

    /**
     * Maps the specified `$type` to the specified `$classname`.
     *
     * @see FinderMap::find()
     *
     * @param string $type
     * @param string $classname
     *
     * @return void
     */
    public function mapType(string $type, string $classname): void
    {
        $this->map[$type] = $classname;
    }

    /**
     * Returns the type-to-classname map array.
     *
     * @return array
     */
    public function getMap(): array
    {
        return $this->map;
    }
}
