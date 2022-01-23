<?php

namespace Zvive\Fixer\Tests\Unit\Commands;

use Zvive\Fixer\Commands\GenerateConfigCommand;
use Zvive\Fixer\Finders\LaravelProjectFinder;
use Zvive\Fixer\Rulesets\DefaultRuleset;
use Zvive\Fixer\Rulesets\ZviveRuleset;
use Zvive\Fixer\SharedConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateConfigCommandTest extends TestCase
{
    protected array $tempFileCache = [];

    protected function setUp(): void
    {
        $this->tempFileCache = [];
    }

    protected function tearDown(): void
    {
        $failures = [];

        foreach ($this->tempFileCache as $filename) {
            if (file_exists($filename) && is_file($filename)) {
                //echo "unlinking " . basename($filename) . "\n";
                if (!unlink($filename)) {
                    $failures[] = $filename;
                }
            }
        }

        // persist any files that couldn't be unlinked to try again on future method invocations
        $this->tempFileCache = $failures;
    }

    public function getRandomConfigFilename(): string
    {
        $seed = str_repeat('abcdefghijklmnopqrstuvwxyz0987654321', 4);

        return './test_config-' . sha1(str_shuffle($seed)) . '.temp';
    }

    public function generateTempFilename(): string
    {
        $targetFile = $this->getRandomConfigFilename();

        $counter = 0;
        while (file_exists($targetFile) && $counter < 100) {
            ++$counter;
            $targetFile = $this->getRandomConfigFilename();
        }

        $this->tempFileCache[] = $targetFile;

        return $targetFile;
    }

    public function getCommand(): GenerateConfigCommand
    {
        $command = new GenerateConfigCommand();
        $command->setName('generate');

        return $command;
    }

    public function getCommandTester(): CommandTester
    {
        $command = $this->getCommand();
        $app = $this->getApp($command);

        return new CommandTester($command);
    }

    public function getApp($command): ?Command
    {
        return (new Application())
            ->add($command)
            ->addArgument('type', InputArgument::OPTIONAL, 'The type of finder to use.', 'laravel')
            ->addOption('ruleset', 'r', InputOption::VALUE_REQUIRED, 'The ruleset to use', 'default')
            ->addOption('outfile', 'o', InputOption::VALUE_REQUIRED, 'The filename to write to', '.php_cs.dist')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Overwrite existing file');
    }

    /** @test */
    public function it_generates_a_config_file(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename"]);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);
    }

    /** @test */
    public function it_generates_a_config_file_with_the_correct_imports(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);

        $content = file_get_contents($filename);

        static::assertStringContainsString('use ' . SharedConfig::class . ';', $content);
        static::assertStringContainsString('use ' . ZviveRuleset::class . ';', $content);
        static::assertStringContainsString('use ' . LaravelProjectFinder::class . ';', $content);
    }

    /** @test */
    public function it_generates_a_config_file_that_creates__ruleset_and__shared_config_instances(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);

        $content = file_get_contents($filename);

        static::assertStringContainsString('new ZviveRuleset()', $content);
        static::assertStringContainsString('SharedConfig::create(', $content);
    }

    /** @test */
    public function it_generates_a_config_file_that_requires_the_autoloader_file(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);

        $content = file_get_contents($filename);

        static::assertMatchesRegularExpression("~^\s*require(_once)?\(__DIR__\s*.\s*'/vendor/autoload.php'\);\s*$~m", $content);
    }

    /** @test */
    public function it_fails_to_generate_a_config_file_when_an_invalid_ruleset_name_is_provided(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'badrulesetname']);

        static::assertEquals(Command::FAILURE, $tester->getStatusCode());
        static::assertFileDoesNotExist($filename);
    }

    /** @test */
    public function it_fails_to_generate_a_config_file_when_an_invalid_type_name_is_provided(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'invalidtypename', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::FAILURE, $tester->getStatusCode());
        static::assertFileDoesNotExist($filename);
    }

    /** @test */
    public function it_fails_when_prompting_the_user_to_overwrite_when_target_config_file_already_exists_and_user_declines(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);

        $tester->setInputs(['no']);
        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::FAILURE, $tester->getStatusCode());
    }

    /** @test */
    public function it_succeeds_when_prompting_the_user_to_overwrite_when_target_config_file_already_exists_and_user_affirms(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);

        $tester->setInputs(['yes']);
        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);
    }

    /** @test */
    public function it_succeeds_in_overwriting_existing_file_when_force_option_is_provided_and_target_config_file_already_exists(): void
    {
        $filename = $this->generateTempFilename();
        $tester = $this->getCommandTester();

        static::assertFileDoesNotExist($filename);
        static::assertNotEmpty($filename);

        $tester->execute(['command' => 'laravel', '-o' => "$filename", '-r' => 'default']);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);

        $tester->execute(['command' => 'laravel', '--force' => true, '-o' => "$filename"]);

        static::assertEquals(Command::SUCCESS, $tester->getStatusCode());
        static::assertFileExists($filename);
    }
}
