<?php

  namespace Zvive\Fixer\Finders\Contracts;

  use PhpCsFixer\Finder;

  interface FinderInterface
  {
    public static function configTypes() : iterable;

    public static function create(string $baseDir): Finder;

    public static function onlyExistingPaths(iterable $paths): iterable;

  }