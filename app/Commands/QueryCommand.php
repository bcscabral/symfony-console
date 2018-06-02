<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\Car;

class QueryCommand extends Command
{
    private $car;

    function __construct()
    {
        $this->car = new Car;
        parent::__construct();
    }

    protected function configure()
    {
        //creating command
        $this
            ->setName('cars:query')
            ->setDescription('Search for car statistics.')
            ->setHelp('This command display statistics, i.e. how many cars are in dataset for each feature')
        ;

        //creating options
        //name, shortcut, mode, description, default value
        $this
            ->addOption('mpg', 'mpg',InputArgument::OPTIONAL, 'Filter by mpg')
            ->addOption('cylinders', 'cylinders', InputArgument::OPTIONAL, 'Filter by cylinders')
            ->addOption('displacement', 'displacement', InputArgument::OPTIONAL, 'Filter by displacement')
            ->addOption('horsepower', 'horsepower', InputArgument::OPTIONAL, 'Filter by horsepower')
            ->addOption('weight', 'weight', InputArgument::OPTIONAL, 'Filter by weight')
            ->addOption('acceleration', 'acceleration', InputArgument::OPTIONAL, 'Filter by acceleration')
            ->addOption('model', 'model', InputArgument::OPTIONAL, 'Filter by model')
            ->addOption('origin', 'origin', InputArgument::OPTIONAL, 'Filter by origin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //calling the query function of class Car
        $list = $this->car->query($input);

        //formatting and writing result when command contains additional options
        if($this->car->getCommandContainsOptions()){
            $output->writeln('Search result:');
            foreach ($list as $item){
                $output->writeln("\t {$item[0]}");
            }
        }
        //formatting and writing result when command does not contains additional options
        else{
            foreach ($list as $item){
                $output->writeln("{$item['name']}");
                foreach ($item['statistics'] as $statistic) {
                    $output->writeln("\t {$statistic}");
                }
            }
        }
    }
}