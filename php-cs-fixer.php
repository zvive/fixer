<?php
require_once(__DIR__.'/vendor/autoload.php');

use Zvive\Fixer\Finders\ComposerPackageFinder;
use Zvive\Fixer\Rulesets\ZviveRuleset;
use Zvive\Fixer\SharedConfig;

// optional: chain additiional custom Finder options:
$finder = ComposerPackageFinder::create(__DIR__);

return SharedConfig::create($finder, new ZviveRuleset());
