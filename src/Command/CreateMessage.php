<?php

namespace App\Command;

use App\Service\MessageCreator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateMessage extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-message';
    
    private $messageCreator;

    public function __construct(MessageCreator $messageCreator)
    {
        $this->messageCreatorService = $messageCreator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('')
            ->setHelp('');

        //$this
        //    // configure an argument
        //    ->addArgument('seed', InputArgument::REQUIRED, 'A word')
        //;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating message');
        $output->writeln('================');

        //$seed = $input->getArgument('seed');

        $newSentence = $this->messageCreatorService->getMessage();

        $output->writeln($newSentence);
        
        $output->writeln("=========");
        $output->writeln("Complete.");
    }
}
