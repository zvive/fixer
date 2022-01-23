<?php

    declare(strict_types=1);

    require __DIR__.'/vendor/autoload.php';

    use Zvive\Fixer\SharedConfig;
    use Zvive\Fixer\Rulesets\ZviveRuleset;
    use Zvive\Fixer\Finders\LaravelPackageFinder;

// optional: chain additional custom Finder options:
    $finder = LaravelPackageFinder::create(__DIR__);

    $finder->notName(['*.ide-helper.php', 'composer.json', '*_ide-helper.php']);

    return SharedConfig::create($finder, new ZviveRuleset());