<?php

namespace Zvive\Fixer\Tests\Unit\Commands\Prompts;

use Zvive\Fixer\Commands\Prompts\ConsoleOverwriteExistingFilePrompt;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleOverwriteExistingFilePromptTest extends TestCase
{
    /** @test */
    public function it_returns_true_when_confirmed_successfully(): void
    {
        $console = $this->createMock(SymfonyStyle::class);

        $console->method('confirm')
            ->withAnyParameters()
            ->willReturn(true);

        $prompt = new ConsoleOverwriteExistingFilePrompt(null, null, null, $console);

        static::assertTrue($prompt->display('test.temp'));
    }

    /** @test */
    public function it_returns_false_when_not_confirmed_successfully(): void
    {
        $console = $this->createMock(SymfonyStyle::class);

        $console->method('confirm')
            ->withAnyParameters()
            ->willReturn(false);

        $output = new class() {
          /**
           * @param array ...$args
           * @return void
           */
          public function writeln(array ...$args):void
            {
            }
        };

        $prompt = new ConsoleOverwriteExistingFilePrompt(null, $output, null, $console);

        static::assertFalse($prompt->display('test.temp'));
    }
}
