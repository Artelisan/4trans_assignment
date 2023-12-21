<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'herdSize',
    description: 'Calculate the number of knights on a chessboard.'
)]
class KnightsCountCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp('This command allows you to calculate how many knights fit on the chessboard.')
            ->addArgument('boardsize', InputArgument::REQUIRED, 'Size of the chessboard');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $boardSize = (int)$input->getArgument('boardsize');

        if ($boardSize <= 0) {
            $output->writeln('Invalid board size. Board size must be a positive integer.');
            return Command::FAILURE;
        }

        $maxKnights = $boardSize !== 2 ? $this->calculateMaxKnights($boardSize, 1, 1, 0) : 4;

        $output->writeln("The maximum number of non-attacking knights on a {$boardSize}x{$boardSize} chessboard is: {$maxKnights}");

        return Command::SUCCESS;
    }

    private function calculateMaxKnights($boardSize, $row, $col, $count): int
    {
        for ($i = $col; $i <= $boardSize; $i = $i + 2) {
            $count++;
        }

        $col = $row % 2 === 0 ? 2 : 1;

        if ($row !== $boardSize) {
            return $this->calculateMaxKnights($boardSize, $row + 1, $col, $count);
        }

        return $count;
    }
}
