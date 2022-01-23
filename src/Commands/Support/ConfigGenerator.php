<?php

namespace Zvive\Fixer\Commands\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use PhpCsFixer\Finder;

class ConfigGenerator
{
  public function __construct(
    public array $includePaths = [],
    public array $excludePaths = [],
    public bool $isCustom = false
  ) {}

  /**
     * Generates a php-cs-fixer config file.
     *
     * @param string $finderName
     * @param string $rulesetClass
     *
     * @return string
     */
    public function generate(string $finderName, string $rulesetClass): string
    {
        //remove the namespace from the finder classname
        $finderNameParts = \explode('\\', $finderName);
        $finderNameShort = \array_pop($finderNameParts);
        $finderCode = $this->isCustom === true
            ? $this->generateCustomFinderCode($finderNameShort)
            : $this->generateFinderCode($finderNameShort);
        $finderCode = \trim($finderCode, "; \t\n\r\0\x0B");
        $rulesetClass = $rulesetClass === 'DefaultRuleset' ? 'ZviveRuleset' : $rulesetClass;
        $code = <<<CODE
            <?php
            require_once(__DIR__ . '/vendor/autoload.php');

            use $finderName;
            use Zvive\\Fixer\\Rulesets\\$rulesetClass;
            use Zvive\\Fixer\\SharedConfig;

            // optional: chain additional custom Finder options:
            \$finder = {$finderCode};

            return SharedConfig::create(\$finder, new $rulesetClass());
            CODE;

        return \trim($code);
    }

  public function generateFinderCode(string $finderNameShort): string
    {
        return <<<CODE
            {$finderNameShort}::create(__DIR__)
              ->ignoreVCS(true)
              ->ignoreDotFiles(true)
              ->name('*.php')
        CODE;
    }
    public function generateCustomFinderCode(string $finderNameShort): string {
      $include = $this->getFinderCodeString('include');
      $exclude = $this->getFinderCodeString('exclude');
        return <<< CODE
            {$finderNameShort}::create(__DIR__)
                ->ignoreVCS(true)
                ->ignoreDotFiles(true)
                ->name('*.php')
                {$include}
                {$exclude}
        CODE;
    }

  public function getFinderCodeString(string $type): string
  {
    $paths = match($type){
      'include' => $this->includePaths,
      'exclude' => $this->excludePaths,
      default  => []
    };
    return $this->getFinderParts($type, $paths);
  }

  public function getCodeCollection(array $paths): Collection
  {
    return Collection::make($paths)
                     ->map(fn ($item) =>  __DIR__."/'{$item}'");
  }

  protected function getFinderParts(string $type, array $paths): string
  {
    $paths = $type === 'exclude' ? [...$paths, __DIR__.'/vendor'] : $paths;
    $code = $this->getCodeCollection(paths: $paths);

    if ($code->count() === 0) {
      return '';
    }


    $code = \trim($code->implode(', '));

    return match ($type) {
      'include' => <<<CODE
            ->in([{$code}]
          CODE,
      'exclude' => <<<CODE
            ->exclude([{$code}],
          CODE,
      default => ''
    };
  }
}
