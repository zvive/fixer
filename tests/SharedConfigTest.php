<?php

namespace Zvive\Fixer\Tests\Unit;

use Zvive\Fixer\Rulesets\ZviveRuleset;
use Zvive\Fixer\Rulesets\SpatieRuleset;
use Zvive\Fixer\SharedConfig;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class SharedConfigTest extends TestCase
{
    /** @test */
    public function it_returns_a_php_cs_fixer_config_object(): void
    {
        $finder = Finder::create();
        $config = SharedConfig::create($finder);

        static::assertInstanceOf(Config::class, $config);
    }

    /** @test */
    public function it_returns_the_default_ruleset_when_none_is_provided_to_the_create_method(): void
    {
        $finder = Finder::create();
        $config = SharedConfig::create($finder);
        $expectedRules = (new ZviveRuleset())->rules();

        static::assertSame($expectedRules, $config->getRules());
    }

    /** @test */
    public function it_returns_the_provided_ruleset_when_one_is_provided_to_the_create_method(): void
    {
        $finder = Finder::create();
        $config = SharedConfig::create($finder, new SpatieRuleset());
        $expectedRules = (new SpatieRuleset())->rules();

        static::assertSame($expectedRules, $config->getRules());
    }
}
