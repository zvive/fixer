<?php

declare(strict_types=1);

namespace Zvive\Fixer\Rulesets;

abstract class BaseRuleset implements Contracts\RuleSetInterface
{
    public function __construct(
        protected array $additional = []
    ) {}

    abstract public function allowRisky() : bool;

    abstract public function rules() : array;
}
