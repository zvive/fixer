<?php

namespace Zvive\Fixer\Commands\Prompts;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleSelectPathsForCustomFinderPrompt
{
  public const INCLUDE_PATHS_PROMPT = 1;
  public const EXCLUDE_PATHS_PROMPT = 2;


  public function __construct(
    protected InputInterface $input,
    protected OutputInterface $output,
    protected ?SymfonyStyle $console = null,
    protected bool $includeNoneOption = true,
    protected ?int $promptType = null
  )  {
    $this->promptType = $promptType ?? self::INCLUDE_PATHS_PROMPT;
  }


    public function display($items, array $excludeItems = []): array
    {
        $excludeItems = array_merge(['node_modules', 'vendor'], $excludeItems);

        if (!$this->hasPreparedItems(is_array($items) ? $items : $items->toArray(), $excludeItems)) {
            return [];
        }

        $console = $this->console ?? new SymfonyStyle(input: $this->input, output: $this->output);

        $result = $console->askQuestion(
            $this->getQuestion($items, $excludeItems)
        );

        return $result[0] !== 'none' ? $result : [];
    }

    public function withNoneOption(bool $value = true): self
    {
        $this->includeNoneOption = $value;

        return $this;
    }

    public function withPromptType(int $type): self
    {
        $this->promptType = $type;

        return $this;
    }

    public function getQuestion($items, array $excludeItems = []): ChoiceQuestion
    {
        $action = $this->promptType === self::EXCLUDE_PATHS_PROMPT
            ? 'IGNORE'
            : 'SEARCH';

        $items = is_array($items) ? $items : $items->toArray();

        $question = new ChoiceQuestion(
            "Please enter a comma-separated list of the directories php-cs-fixer should <fg=yellow;bg=default;options=bold>$action</>",
            $this->prepareItems($items, $excludeItems)
        );

        return $question->setMultiselect(true);
    }

    public function prepareItems(array $items, array $excludeItems): array
    {
        if ($this->includeNoneOption) {
            array_unshift($items, 'none');
        }

        return Collection::make($items)
            ->filter(function($item) use ($excludeItems) {
                return !Str::startsWith($item, '.') && !in_array($item, $excludeItems, true);
            })
            ->values()
            ->toArray();
    }

    public function hasPreparedItems(array $items, array $exclude): bool
    {
        $prepared = Collection::make($this->prepareItems($items, $exclude));

        return $prepared->isNotEmpty();
    }
}
