<?php

namespace Leezy\PheanstalkBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StatsJobCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('leezy:pheanstalk:stats-job')
            ->addArgument('tube', InputArgument::REQUIRED, 'Tube to get stats.')
            ->addArgument('job', InputArgument::REQUIRED, 'Jod id to get stats.')
            ->setDescription('Gives statistical information about the specified job if it exists.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tube = $input->getArgument('tube');
        $jobId = $input->getArgument('job');
        
        $pheanstalk = $this->getContainer()->get("leezy.pheanstalk");
        $job = $pheanstalk->peek($jobId);
        $stats = $pheanstalk->statsJob($job);
        
        if (count($stats) === 0 ) {
            $output->writeln('<info>no stats.</info>');
        }
        
        foreach ($stats as $key => $information) {
            $output->writeln('<info>' . $key . '</info> : ' . $information);
        }
    }
}