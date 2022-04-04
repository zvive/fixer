<?php

declare(strict_types=1);

namespace Zvive\Fixer\Rulesets;

use function array_merge;
use Zvive\Fixer\Rulesets\Concerns\BoolRules;
use Zvive\Fixer\Rulesets\Concerns\CustomRules;

class ZviveRuleset extends LaravelShiftRuleset
{
    use BoolRules;
    use CustomRules;
    public array $falseRulesets = [
      'not_operator_with_space',
      'not_operator_with_successor_space',
    ];
    public array $trueRulesets = [
        'single_blank_line_before_namespace',
        'single_import_per_statement',
        'array_indentation',
        'assign_null_coalescing_to_coalesce_equal',
        'backtick_to_shell_exec',
        'blank_line_after_opening_tag',
        //          'clean_namespace',
        'combine_consecutive_issets',
        'combine_consecutive_unsets',
        'compact_nullable_typehint',
        'declare_equal_normalize',
        'declare_strict_types',
        'explicit_indirect_variable',
        'explicit_string_variable',
        'fully_qualified_strict_types',
        'function_typehint_space',
        // 'group_import',
        'indentation_type',
        'lambda_not_used_import',
        'line_ending',
        'linebreak_after_opening_tag',
        'logical_operators',
        'lowercase_cast',
        'lowercase_keywords',
        'lowercase_static_reference',
        'method_chaining_indentation',
        'multiline_comment_opening_closing',
        'modernize_strpos', // risky
        'modernize_types_casting', // risky
        'native_function_casing',
        'native_function_type_declaration_casing',
        'new_with_braces',
        // 'no_blank_lines_before_namespace',
        'no_blank_lines_after_class_opening',
        'no_alias_language_construct_call',
        'no_blank_lines_after_phpdoc',
        'no_closing_tag',
        // 'no_empty_comment',
        // 'no_empty_phpdoc',
        'no_empty_statement',
        'no_leading_import_slash',
        'no_multiline_whitespace_around_double_arrow',
        'no_unset_cast',
        'no_php4_constructor',
        'no_singleline_whitespace_before_semicolons',
        'no_spaces_after_function_name',
        'no_space_around_double_colon',
        'no_spaces_around_offset',
        'no_spaces_inside_parenthesis',
        'no_superfluous_elseif',
        'no_trailing_comma_in_singleline_array',
        'no_trailing_whitespace',
        'no_trailing_whitespace_in_comment',
        'no_unused_imports',
        'no_useless_else',
        'no_useless_return',
        'no_unset_cast',
        'no_whitespace_before_comma_in_array',
        'no_whitespace_in_blank_line',
        'normalize_index_brace',
        'object_operator_without_whitespace',
        'phpdoc_add_missing_param_annotation',
        'phpdoc_order',
        'protected_to_private',
        'psr_autoloading',
        'return_assignment',
        'semicolon_after_instruction',
        'short_scalar_cast',
        'simplified_if_return',
        'simplified_null_return',
        'simple_to_complex_string_variable',
        //          'single_blank_line_before_namespace',
        'single_trait_insert_per_statement',
        'strict_comparison',
        'strict_param',
        'switch_continue_to_break',
        'ternary_operator_spaces',
        'trim_array_spaces',
        'unary_operator_spaces',
        'use_arrow_functions',

    ];

    public function rules() : array
    {
        $rules = [
            // '@psr2' => true,

            'align_multiline_comment' => ['comment_type' => 'phpdocs_like'],
            'array_syntax'            => ['syntax' => 'short'],

            'binary_operator_spaces' => [
                'default'   => 'align_single_space_minimal',
                'operators' => ['=>' => 'align_single_space_minimal'],
            ],

            'braces' => [
                'allow_single_line_closure'                         => true,
                'allow_single_line_anonymous_class_with_empty_body' => true,
                'position_after_anonymous_constructs'               => 'same',
                'position_after_control_structures'                 => 'same',
                'position_after_functions_and_oop_constructs'       => 'next',
            ],
            'class_attributes_separation' => [
                'elements' => [
                    'const'        => 'only_if_meta',
                    'method'       => 'one',
                    'property'     => 'only_if_meta',
                    'trait_import' => 'none',
                ],
            ],

            'class_definition' => [
                'single_item_single_line' => true,
                'single_line'             => true,
            ],

            // 'function_declaration'                    => [
            //     'closure_function_spacing' => 'none',
            // ],

            'heredoc_indentation'     => ['indentation' => 'start_plus_one'],
            'global_namespace_import' => [
                'import_classes' => true,
                // 'import_constants' => true,
                // 'import_functions' => true,
            ],

            'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
            // ['strategy' => 'new_line_for_chained_calls'],

            'native_function_invocation' => [
                'include' => ['@all'],
                'scope'   => 'namespaced',
                'strict'  => true,
            ],

            'no_alias_functions' => [
                'sets' => ['@all'],
            ],

            // 'no_extra_blank_lines' => [
            //     'tokens' => [
            //         // 'curly_brace_block',
            //         // 'parenthesis_brace_block',
            //         // 'default',
            //         'extra',
            //         'use',
            //         'use_trait',
            //     ],
            // ],

            //              'no_superfluous_phpdoc_tags' => [
            //                  'allow_unused_params' => false,
            //                  'remove_inheritdoc'   => true,
            //              ],

            'method_argument_space' => [
                'on_multiline' => 'ensure_fully_multiline',
            ],
            'ordered_class_elements' => [
                'order' => [
                    'use_trait',
                    'constant_public',
                    'constant_protected',
                    'constant_private',
                    'property_public',
                    'property_protected',
                    'property_private',
                    'construct',
                    'destruct',
                    'magic',
                    'phpunit',
                    'method',
                ],
                'sort_algorithm' => 'alpha',
            ],
            'ordered_imports' => [
                'sort_algorithm' => 'length',
            ],

            'phpdoc_to_comment'  => ['ignored_tags' => ['internal', 'var', 'mixin', 'todo']],
            'phpdoc_types_order' => [
                'sort_algorithm'  => 'alpha',
                'null_adjustment' => 'always_last',
            ],

            'return_type_declaration' => ['space_before' => 'one'],

        ];

        return array_merge(parent::rules(), $this->boolRules(), $rules, $this->additional, $this->getCustomRules()); // it's important that the additional rules property is merged
    }
}
