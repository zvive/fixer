<?php

namespace Zvive\Fixer\Tests\Unit\Rulesets;

use Zvive\Fixer\Rulesets\LaravelShiftRuleset;
use Zvive\Fixer\Tests\Unit\RulesetTestCase;

class LaravelShiftRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return LaravelShiftRuleset::class;
    }
}
