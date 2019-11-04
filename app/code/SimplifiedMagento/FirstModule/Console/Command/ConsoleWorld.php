<?php

namespace SimplifiedMagento\FirstModule\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleWorld extends Command
{
    /**
     *
     */
    public function configure()
    {
        $this->setName('training:sayhello')
            ->setDescription('Demo command line')
            ->setAliases(array('hw'));

        $this->setDefinition([
            new InputArgument("name",InputArgument::OPTIONAL,"the name of the person",null,null)
        ]);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('hello world,'.$input->getArgument("name"));
    }
}
