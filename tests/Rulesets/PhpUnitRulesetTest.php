<?php

namespace Zvive\Fixer\Tests\Unit\Rulesets;

use Zvive\Fixer\Rulesets\PhpUnitRuleset;
use Zvive\Fixer\Tests\Unit\RulesetTestCase;

class PhpUnitRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return PhpUnitRuleset::class;
    }
}
