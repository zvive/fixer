<?php

namespace Zvive\Fixer\Tests\Unit;

use Zvive\Fixer\Rulesets\Contracts\RuleSetInterface as RuleSet;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

/**
 * BaseRuleset `TestCase` class for `Ruleset` unit tests, because all tests are identical except for
 * the `Ruleset` class being tested.
 */
abstract class RulesetTestCase extends TestCase
{
    abstract public function getRulesetClass(): string;

    public function getRuleset(array $args = [])
    {
        $class = $this->getRulesetClass();

        return new $class($args);
    }

    /** @test */
    public function it_implements_the_ruleset_contract(): void
    {
        $ruleset = $this->getRuleset();

        static::assertInstanceOf(RuleSet::class, $ruleset);
    }

    /** @test */
    public function it_implements_only_interface_and_optional_trait_methods(): void
    {
        $reflect = new \ReflectionClass($this->getRuleset());
        static::assertLessThanOrEqual(9, count($reflect->getMethods(\ReflectionMethod::IS_PUBLIC)));
    }

//    /** @test */
//    public function it_returns_a_valid_name(): void
//    {
//        $ruleset = $this->getRuleset();
//
//        $expectedName = Str::afterLast($ruleset::name(), '\\');
//        $expectedName = Str::snake(str_replace('Ruleset', '', $expectedName));
//
//        static::assertIsString($ruleset::name());
//        static::assertNotEmpty($ruleset::name());
//        static::assertEquals($expectedName, strtolower($ruleset::name()));
//    }

    /** @test */
    public function it_returns_valid_rules(): void
    {
        $ruleset = $this->getRuleset();

        static::assertIsArray($ruleset->rules());
        static::assertNotEmpty($ruleset->rules());
    }

    /** @test */
    public function it_returns_a_bool_from_allow_risky_method(): void
    {
        $ruleset = $this->getRuleset();

        static::assertIsBool($ruleset->allowRisky());
    }

    /** @test */
    public function it_merges_additional_rules(): void
    {
        $rulesetBase = $this->getRuleset();
        $baseRules = $rulesetBase->rules();

        $ruleset = $this->getRuleset(['__MERGED_RULE__' => 12]);
        $rules = $ruleset->rules();

        static::assertIsArray($rules);
        static::assertCount(count($baseRules) + 1, $rules);
        static::assertArrayHasKey('__MERGED_RULE__', $rules);
        static::assertEquals(12, $rules['__MERGED_RULE__']);
    }
}
