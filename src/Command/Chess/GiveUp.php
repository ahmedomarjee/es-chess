<?php

namespace App\Command\Chess;

use App\Domain\ChessFacade;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GiveUp extends Command
{
    protected static $defaultName = 'chess:give_up';
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
            ->setDescription('Give up an event-sourced chess game')
            ->addArgument('game_id', InputArgument::REQUIRED)
            ->addArgument('player', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->chessFacade->giveUp(
                $input->getArgument('game_id'),
                $input->getArgument('player'),
            );

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }
    }
}
