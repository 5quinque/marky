<?php

namespace App\Command;

use App\Service\MessageParser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadMessages extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:load-messages';
    
    private $messageParserService;

    public function __construct(MessageParser $messageParserService)
    {
        $this->messageParserService = $messageParserService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('')
            ->setHelp('');

        $this
            // configure an argument
            ->addArgument('filename', InputArgument::REQUIRED, 'The filename to parse.')
            // ...
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Loading messages');
        $output->writeln('================');

        $filename = $input->getArgument('filename');

        $this->messageParserService->setFile($filename);

        $this->messageParserService->loadMessages();
        
        $output->writeln("=========");
        $output->writeln("Complete.");
    }
}
