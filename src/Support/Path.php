<?php

namespace Zvive\Fixer\Support;
use Illuminate\Support\Collection;
class Path
{
    public static function getSubDirectoryNames(string $path, array $excludeNames = []): Collection
    {
        if (!file_exists($path) || !is_dir($path)) {
            return Collection::make([]);
        }

        $files = scandir($path);

        return Collection::make($files)
//            ->except(['.', '..'])
//            ->except($excludeNames)
            ->filter(function($item) use ($excludeNames) {
                return !in_array($item,array_merge($excludeNames, ['.', '..']), true) && is_dir
                  ($item);
            })
            ->values();
    }
}
