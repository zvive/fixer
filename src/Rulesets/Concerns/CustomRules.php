<?php declare(strict_types=1);
namespace Zvive\Fixer\Rulesets\Concerns;

  use function collect;

  /**
   * CustomRules for @php-cs-fixer.
   *
   * @see https://github.com/kubawerlos/php-cs-fixer-custom-fixers
   */
  trait CustomRules
  {
      protected array $customRules = [
          'PromotedConstructorPropertyFixer'         => true,
          'CommentSurroundedBySpacesFixer'           => true,
          'ConstructorEmptyBracesFixer'              => true,
          'DeclareAfterOpeningTagFixer'              => false,
          'InternalClassCasingFixer'                 => true,
          'IssetToArrayKeyExistsFixer'               => true,
          'MultilineCommentOpeningClosingAloneFixer' => true,
          'MultilinePromotedPropertiesFixer'         => true,
          'NoUselessCommentFixer'                    => false,
          'NoDuplicatedImportsFixer'                 => true,
          //          'NoImportFromGlobalNamespaceFixer'         => true,
          'NoDuplicatedArrayKeyFixer'            => true,
          'NoLeadingSlashInGlobalNamespaceFixer' => false,
          'NoNullableBooleanTypeFixer'           => true,
          'NoPhpStormGeneratedCommentFixer'      => true,
          'NoSuperfluousConcatenationFixer'      => true,
          'NoUselessDirnameCallFixer'            => true,
          'NoUselessParenthesisFixer'            => true,
          'NoUselessStrlenFixer'                 => true,
          'PhpUnitAssertArgumentsOrderFixer'     => true,
          'PhpUnitDedicatedAssertFixer'          => true,
          'DataProviderStaticFixer'              => true,
          'PhpUnitNoUselessReturnFixer'          => true,
          'PhpdocArrayStyleFixer'                => true,
          'PhpdocNoIncorrectVarAnnotationFixer'  => true,
          'PhpdocNoSuperfluousParamFixer'        => false,
          'PhpdocParamOrderFixer'                => true,
          'PhpdocParamTypeFixer'                 => true,
          'PhpdocSelfAccessorFixer'              => true,
          'PhpdocTypesTrimFixer'                 => true,
          'SingleSpaceAfterStatementFixer'       => true,
          'SingleSpaceBeforeStatementFixer'      => true,
          'StringableInterfaceFixer'             => false,
      ];

      /**
       * @return array
       */
      public function getCustomRules() : array
      {
          return collect($this->customRules)->mapWithKeys(fn ($rule, $key) => [$this->ruleName($key) => $this->customRules[$key]])->toArray();
      }

      /**
       * @param  string  $name
       *
       * @return string
       */
      public function ruleName(string $name) : string
      {
          $className = "PhpCsFixerCustomFixers\\Fixer\\{$name}";
          $class     = new $className();

          return $class::name();
      }
  }