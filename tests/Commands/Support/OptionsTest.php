<?php

namespace Zvive\Fixer\Tests\Unit\Commands\Support;

use parallel\Events\Input;
use Symfony\Component\Console\Input\InputInterface;
use Zvive\Fixer\Commands\Support\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
  /**
   * @return InputInterface
   */
  protected function getMockInputClass()
    {
       return $this->createMock(InputInterface::class);
//       return $input;
//       $input->setOption()
//        return new class()  {
//            public array $options = [];
//            public string $firstArg = '';
//
//          /**
//           * @param string $name
//           * @param string $value
//           * @return void
//           */
//          public function setOption(string $name, string $value): void
//            {
//                $this->options[$name] = $value;
//            }
//
//          /**
//           * @param string $name
//           * @return bool
//           */
//          public function hasOption(string $name): bool
//            {
//                return !empty($this->options[$name]);
//            }
//
//          /**
//           * @param string $name
//           * @return ?string
//           */
//          public function getOption(string $name): ?string
//          {
//                return $this->options[$name] ?? '';
//          }
//
//          /**
//           * @return string
//           */
//          public function getFirstArgument(): string
//            {
//                return $this->firstArg;
//            }
//        };
    }

    /** @test */
    public function it_returns_the_correct_filename(): void
    {
        $input = $this->getMockInputClass();
        $input->setOption('outfile', 'test.conf');
        $input->firstArg = 'project';

        $options = new Options(input: $input);
        static::assertEquals('test.conf', $options->filename());

        $input = $this->getMockInputClass();
        $input->firstArg = 'project';
        $options = new Options($input);

        static::assertNotEmpty($options->filename());
    }

    /** @test */
    public function it_returns_the_correct_overwrite_existing_file_value(): void
    {
        $input = $this->getMockInputClass();
        $input->setOption('force', true);
        $input->firstArg = 'project';

        $options = new Options($input, $ruleset = 'test', true, $filename = 'test.conf');
        static::assertTrue($options->forceOverwrite());

        $input->setOption('force', false);
        $options = new Options($input);
        static::assertFalse($options->forceOverwrite());
    }

    /** @test */
    public function it_returns_the_correct_type_name(): void
    {
        $input = $this->getMockInputClass();
        $input->firstArg = 'test_type';

        $options = new Options($input);

        static::assertEquals('test_type', $options->type());
    }

    /** @test */
    public function it_returns_the_correct_ruleset_name(): void
    {
        $input = $this->getMockInputClass();
        $input->setOption('ruleset', 'TestRuleset');
        $input->firstArg = 'test_type';

        $options = new Options($input);

        static::assertEquals('TestRuleset', $options->ruleset());
    }
}
