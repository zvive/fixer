<?php

namespace Zvive\Fixer\Tests\Unit\Rulesets;

use Zvive\Fixer\Rulesets\SpatieRuleset;
use Zvive\Fixer\Tests\Unit\RulesetTestCase;

class SpatieRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return SpatieRuleset::class;
    }
}
