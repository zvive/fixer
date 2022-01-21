<?php

namespace Zvive\Fixer\Tests\Unit\Rulesets;

use Zvive\Fixer\Rulesets\ZviveRuleset;
use Zvive\Fixer\Tests\Unit\RulesetTestCase;

class ZviveRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return ZviveRuleset::class;
    }
}
