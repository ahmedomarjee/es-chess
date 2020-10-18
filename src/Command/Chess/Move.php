<?php

namespace App\Command\Chess;

use App\Domain\ChessFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Move extends Command
{
    protected static $defaultName = 'chess:move';
    protected ChessFacade $chessFacade;

    public function __construct(string $name = null, ChessFacade $chessFacade)
    {
        $this->chessFacade = $chessFacade;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setName('app:chess')
            ->setDescription('Make move on event-sourced chess game')
            ->addArgument('game_id', InputArgument::REQUIRED)
            ->addArgument('player', InputArgument::REQUIRED)
            ->addArgument('move', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->chessFacade->makeMove(
                $input->getArgument('game_id'),
                $input->getArgument('player'),
                $input->getArgument('move')
            );
            $output->writeln(sprintf('%s made move %s',
                $input->getArgument('player'),
                $input->getArgument('move'),
                $input->getArgument('game_id')
            ));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }
    }
}
