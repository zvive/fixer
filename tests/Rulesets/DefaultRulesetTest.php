<?php

namespace Zvive\Fixer\Tests\Unit\Rulesets;

use Zvive\Fixer\Rulesets\DefaultRuleset;
use Zvive\Fixer\Tests\Unit\RulesetTestCase;

class DefaultRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return DefaultRuleset::class;
    }
}
