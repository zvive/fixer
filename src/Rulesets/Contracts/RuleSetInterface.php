<?php

declare(strict_types=1);

namespace Zvive\Fixer\Rulesets\Contracts;

interface RuleSetInterface
{
    public function allowRisky() : bool;

    public function rules() : array;
}
