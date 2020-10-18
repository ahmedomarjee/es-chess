<?php

namespace App\Command\Chess;

use App\Domain\ChessFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Start extends Command
{
    protected static $defaultName = 'chess:start';
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
            ->setDescription('Start new event-sourced chess game')
            ->addArgument('player1', InputArgument::REQUIRED)
            ->addArgument('player2', InputArgument::REQUIRED)
            ->addArgument('game_name', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $gameId = $this->chessFacade->createGame(
                $input->getArgument('player1'),
                $input->getArgument('player2'),
                $input->getArgument('game_name')
            );
            $output->writeln(sprintf("Game with id: '%s' created", $gameId));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }
    }
}
