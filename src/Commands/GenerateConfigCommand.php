<?php


namespace Zvive\Fixer\Commands;

use PhpCsFixer\Finder;
use Zvive\Fixer\Commands\Prompts\ConsoleOverwriteExistingFilePrompt;
use Zvive\Fixer\Commands\Prompts\ConsoleSelectPathsForCustomFinderPrompt;
use Zvive\Fixer\Commands\Support\ConfigGenerator;
use Zvive\Fixer\Commands\Support\FinderMap;
use Zvive\Fixer\Commands\Support\Options;
use Zvive\Fixer\Finders\BasicProjectFinder;
use Zvive\Fixer\Finders\ComposerPackageFinder;
use Zvive\Fixer\Finders\LaravelPackageFinder;
use Zvive\Fixer\Finders\LaravelProjectFinder;
use Zvive\Fixer\Rulesets\DefaultRuleset;
use Zvive\Fixer\Rulesets\ZviveRuleset;
use Zvive\Fixer\Rulesets\LaravelShiftRuleset;
use Zvive\Fixer\Rulesets\PhpUnitRuleset;
use Zvive\Fixer\Rulesets\SpatieRuleset;
use Zvive\Fixer\Support\Path;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateConfigCommand extends Command
{

    /** @inheritdoc  */
    public function __construct(
      protected OutputInterface $output,
      protected InputInterface $input,
      protected FinderMap $finderMap,
      protected Options $options,
      string $name = null
    )
    {
        $this->finderMap = new FinderMap($this->finders());
        $this->finderMap->mapType('custom', classname: Finder::class);

        parent::__construct($name);
    }


    protected function finders(): array
    {
        return [
            BasicProjectFinder::class,
            ComposerPackageFinder::class,
            LaravelPackageFinder::class,
            LaravelProjectFinder::class,
        ];
    }

    public function rulesets(): array
    {
        return [
          DefaultRuleset::class,
          LaravelShiftRuleset::class,
          PhpUnitRuleset::class,
          SpatieRuleset::class,
          ZviveRuleset::class,
        ];
    }

    /**
     * Returns an array of all valid configuration type names.
     *
     * @return array
     */
    protected function types(): array
    {
        $result = \array_keys($this->finderMap->getMap());

        \sort($result);

        return $result;
    }

    /**
     * Returns the fully-qualified output filename.
     *
     * @return string
     */
    protected function getOutputFilename(): string
    {
        return getcwd() . DIRECTORY_SEPARATOR . $this->options->filename();
    }

    /**
     * Validates user-provided input:
     *   - ruleset flag
     *   - type parameter.
     *
     * Returns true if the user input is valid, otherwise false.
     *
     * @return bool
     */
    protected function validUserInput(): bool
    {
        if (!in_array($this->options->ruleset(), $this->rulesets(), true)) {
            $this->output->writeln('<comment>Ruleset not found.</comment>');
            $this->output->writeln('<comment>Valid rulesets: ' . implode(', ', $this->rulesets()) . '.</comment>');

            return false;
        }

        if (!in_array($this->options->type(), $this->types(), true)) {
            $this->output->writeln('<comment>Invalid type.</comment>');
            $this->output->writeln('<comment>Valid types: ' . implode(', ', $this->types()) . '.</comment>');

            return false;
        }

        return true;
    }

    /**
     * Generates the configuration file and tries to write the contents to file.
     * Returns true on success or false if the file could not be written.
     *
     * @param ConfigGenerator $generator
     *
     * @return bool
     */
    protected function generateAndSaveCode(ConfigGenerator $generator): bool
    {
        $type = $this->options->type();
        $ruleset = Str::studly($this->options->ruleset()) . 'Ruleset';
        $code = $generator->generate($this->finderMap->find($type), $ruleset);

        if (file_put_contents($this->getOutputFilename(), $code) === false) {
            $this->output->writeln('<comment>Failed to write to output file.</comment>');

            return false;
        }

        return true;
    }

    /**
     * If the --force flag was not provided, display a prompt to the user asking if they want to
     * overwrite the existing file.
     *
     * Returns true if the file should be overwritten, otherwise false.
     *
     * @return bool
     */
    protected function forceOverwrite(): bool
    {
        if (!$this->options->force()) {
            $prompt = new ConsoleOverwriteExistingFilePrompt($this->input, $this->output, $this);

            return $prompt->display($this->options->filename());
        }

        return true;
    }

    /**
     * Returns true if the output file exists, otherwise false.
     *
     * @return bool
     */
    protected function outputFileExists(): bool
    {
        return \file_exists($this->getOutputFilename())
            && !\is_dir($this->getOutputFilename());
    }

  /**
   * @return ConfigGenerator
   */
  protected static function makeCustomGenerator():ConfigGenerator
    {
        $prompt = new ConsoleSelectPathsForCustomFinderPrompt($this->input, $this->output);
        $dirNames = Path::getSubDirectoryNames(\getcwd());
        $include = $prompt->withPromptType(1)
                          ->withNoneOption(false)
                          ->display($dirNames);
        $exclude = $prompt->withPromptType(2)
                          ->withNoneOption(true)
                          ->display($dirNames, $include);

        return new ConfigGenerator($include, $exclude, true);
    }


    protected function makeGenerator(): ConfigGenerator
    {
        return new ConfigGenerator();
    }

    /**
     * Generates and saves the code using the correct generator based on the config type.
     * Uses CustomConfigGenerator if the type is 'custom', otherwise uses ConfigGenerator.
     *
     * @see GenerateConfigCommand::makeGenerator()
     * @see GenerateConfigCommand::createCustomConfigGenerator()
     *
     * @return bool
     */
    protected function runCodeGenerator(): bool
    {
        if ($this->options->type() === 'custom') {
            $generator = static::makeCustomGenerator();
        } else {
            $generator = $this->makeGenerator();
        }

        return $this->generateAndSaveCode($generator);
    }

    /**
     * Initializes instance properties.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function executeInit(InputInterface $input, OutputInterface $output): void
    {
        $this->output = $output;
        $this->input = $input;
        $this->options = new Options($input);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->executeInit($input, $output);

        $filename = $this->options->filename();

        if (!$this->validUserInput()) {
            $this->setCode(fn() => Command::FAILURE);
        }

        if ($this->outputFileExists() && !$this->forceOverwrite()) {
            $this->setCode(fn() => Command::FAILURE);
        }

        if (!$this->runCodeGenerator()) {
           $this->setCode(fn() => Command::FAILURE);
        }

        $this->output->writeln("<info>Successfully wrote configuration file '{$filename}'.</info>");

        $this->setCode(fn() => Command::SUCCESS);
        return parent::execute($input, $output);
    }
}
