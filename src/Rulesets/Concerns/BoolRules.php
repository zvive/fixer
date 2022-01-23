<?php declare(strict_types=1);
namespace Zvive\Fixer\Rulesets\Concerns;

  use Illuminate\Support\Collection;

  /**
   * Converts a collection of rules to bools by their key.
   */
  trait BoolRules
  {
      /**
       * @return array
       */
      public function boolRules() : array
      {
          $false = $this->falseRules();

          return $false->merge($this->trueRules())->sortKeys()->toArray();
      }

      /**
       * @return \Illuminate\Support\Collection
       */
      public function falseRules() : Collection
      {
          return Collection::make($this->falseRulesets)
              ->mapWithKeys(fn ($rule) => [$rule => false])
          ;
      }

      // public function setRules(array $rules, bool $boolValue) : Collection {
      //     return \collect($rules)->mapWithKeys(fn($rule) => [$rule => $boolValue]);
      // }

      /**
       * @return \Illuminate\Support\Collection
       */
      public function trueRules() : Collection
      {
          return Collection::make($this->trueRulesets)
              ->mapWithKeys(fn ($rule) => [$rule => true])
          ;
      }
  }