<?php

namespace Zvive\Fixer\Commands\Support;

use Symfony\Component\Console\Input\InputInterface;

class Options
{

  public function __construct(
    protected InputInterface $input,
    protected ?string $ruleset=null,
    protected ?string $type=null,
    protected ?string $filename=null,
    protected bool $force = false,

  )
    {
        $this->input = $input;

        $this->initialize();
    }

  protected function initialize(string $defaultFilename = '.php-cs-fixer.dist.php'): void
    {
        $this->filename = $this->input->hasOption('outfile')
            ? $this->input->getOption('outfile')
            : $defaultFilename;

        $this->force = $this->input->hasOption('force');

        $this->ruleset = $this->input->hasOption('ruleset')
            ? $this->input->getOption('ruleset')
            : 'default';

        $this->type = \strtolower($this->input->getFirstArgument());
    }

  public function ruleset(): string
    {
        return $this->ruleset;
    }

  public function type(): string
    {
        return $this->type;
    }

  public function force(): bool
    {
        return $this->force;
    }

  public function filename(): string
    {
        return $this->filename;
    }
}
