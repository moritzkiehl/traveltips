<?php

namespace Werkraum\SitePackage\Command;

use Helhum\Typo3Console\Mvc\Cli\CommandDispatcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Services.php
 *
 * $services->set(\Werkraum\SitePackage\Command::class)
 *   ->tag('console.command', [
 *       'command' => 'database:dump',
 *       'schedulable' => false
 *   ]);
 *
 * Command usage: "vendor/bin/typo3cms database:dump"
 */
final class DatabaseDumpCommand extends Command
{
    private readonly CommandDispatcher $commandDispatcher;

    public function __construct(string $name = null, CommandDispatcher $commandDispatcher = null)
    {
        parent::__construct($name);
        $this->commandDispatcher = $commandDispatcher ?: CommandDispatcher::createFromCommandRun();
    }

    protected function configure(): void
    {
        $this->setDescription('create a minimal, optimized database dump');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = 'dump-' . (new \DateTime())->format('Y-m-d-H-i') . '.sql';

        $data = $this->commandDispatcher->executeCommand('database:export', [
            '--connection', 'Default',
            '--exclude', 'cf_*',
            '--exclude', 'cache_*',
            '--exclude', '[bf]e_sessions',
            '--exclude', '[bf]e_users',
            '--exclude', 'sys_log',
            '--exclude', 'sys_history',
            '--exclude', 'sys_registry',
            '--exclude', 'tx_solr_*',
            '--exclude', 'tx_igldapssoauth_config',
        ]);

        if (function_exists('gzencode')) {
            $data = gzencode($data, 9);
            $fileName .= '.gz';
        }

        file_put_contents($fileName, $data);

        return Command::SUCCESS;
    }
}
