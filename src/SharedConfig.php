<?php

declare(strict_types=1);

namespace Zvive\Fixer;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\ConfigInterface;
use Zvive\Fixer\Finders\BaseFinder;
use Zvive\Fixer\Rulesets\Contracts\RuleSetInterface;
use Zvive\Fixer\Rulesets\ZviveRuleset;
use PhpCsFixerCustomFixers\Fixers as CustomFixers;

class SharedConfig
{
    public static function create($finder, ?RuleSetInterface $ruleset = null) :
    ConfigInterface
    {
        if ($ruleset === null) {
            $ruleset = new ZviveRuleset();
        }
        return (new Config())
            ->registerCustomFixers(fixers:new CustomFixers())
            ->setFinder(finder:$finder->create())
            ->setRiskyAllowed(isRiskyAllowed:$ruleset->allowRisky())
            ->setRules($ruleset->rules())
        ;
    }
}