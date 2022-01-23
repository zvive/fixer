<?php

namespace Zvive\Fixer\Tests\Unit\Commands\Prompts;

use Zvive\Fixer\Commands\Prompts\ConsoleSelectPathsForCustomFinderPrompt;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleSelectPathsForCustomFinderPromptTest extends TestCase
{
    /** @test */
    public function it_prepares_items_for_display(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null);

        static::assertSame(['none', 'aaa', 'bbb'], $prompt->withNoneOption(true)->prepareItems(['aaa', 'bbb'], []));
        static::assertSame(['aaa', 'bbb'], $prompt->withNoneOption(false)->prepareItems(['aaa', 'bbb'], []));
        static::assertSame(['aaa'], $prompt->withNoneOption(false)->prepareItems(['aaa'], []));
        static::assertSame(['aaa', 'bbb'], $prompt->withNoneOption(false)->prepareItems
        (Collection::make(['aaa', 'bbb'])->toArray(), []));
        static::assertSame(['aaa'], $prompt->withNoneOption(false)->prepareItems(Collection::make
        (['aaa', 'bbb'])->toArray(), ['bbb']));
    }

    /** @test */
    public function it_returns_a__choice_question_instance(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null);
        $question = $prompt->getQuestion(['aaa']);

        static::assertInstanceOf(ChoiceQuestion::class, $question);
    }

    /** @test */
    public function it_configures_the_question_instance_correctly(): void
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null);
        $question = $prompt->getQuestion(['aaa']);

        static::assertTrue($question->isMultiselect());
    }

    /** @test */
    public function it_returns_an_array_after_prompting()
    {
        $console = $this->createMock(SymfonyStyle::class);

        $console->method('askQuestion')
            ->withAnyParameters()
            ->willReturn(['bbb']);

        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, $console);
        $result = $prompt
            ->withNoneOption(false)
            //->excludePaths(['aaa'])
            ->withPromptType(1)
            ->display(['aaa', 'bbb']);

        static::assertSame(['bbb'], $result);
    }

    /** @test */
    public function it_returns_an_empty_array_after_prompting_returns_none_item()
    {
        $console = $this->createMock(SymfonyStyle::class);

        $console->method('askQuestion')
            ->withAnyParameters()
            ->willReturn(['none']);

        $prompt = new ConsoleSelectPathsForCustomFinderPrompt(null, null, $console);
        $result = $prompt
            ->withNoneOption(true)
            //->excludePaths(['aaa'])
            ->withPromptType(1)
            ->display(['aaa', 'bbb']);

        static::assertEmpty($result);
    }
}
