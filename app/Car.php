<?php

namespace App;

class Car
{
    private $options;
    private $cars;

    public function __construct()
    {
        $this->setOptions();
        $this->readCsv();
    }

    private function setOptions()
    {
        $this->options =
            [
                'commandContainsOptions' => false,
                'list' => [
                    1 => 'mpg',
                    2 => 'cylinders',
                    3 => 'displacement',
                    4 => 'horsepower',
                    5 => 'weight',
                    6 => 'acceleration',
                    7 => 'model',
                    8 => 'origin',
                ]
            ];
    }

    /**
     * @return bool
     */
    public function getCommandContainsOptions()
    {
        return $this->options['commandContainsOptions'];
    }

    /**
     * reading the csv file and mapping to array
     */
    private function readCsv()
    {
        $this->cars = array_map(
            function($csv){
                return str_getcsv($csv, ";");
            },
            file(getcwd().'/app/Csvs/cars.csv')
        );
    }

    /**
     * performs array search
     *
     * @return array
     */
    public function query($input)
    {
        //local array with csv data
        $list = $this->cars;

        //verifies if any options were requested, and if positive it calls filter and control variable set as true.
        foreach($this->options['list'] as $key => $val) {
            if( $input->getOption($val) ){
                $this->options['commandContainsOptions'] = true;
                $list = $this->filter($list, $key, $input->getOption($val));
            }
        }

        //if no option was requested it calls function that displays statistics
        if(!$this->options['commandContainsOptions']){
            $list = $this->statistics();
        }

        return $list;
    }

    /**
     * delete items that are not according to filter
     *
     * @return array
     **/
    private function filter($list, $key, $value)
    {
        foreach ($list as $k => $v){
            if ($v[$key] != $value) {
                unset($list[$k]);
            }
        }

        return $list;
    }

    /**
     * check the statistics
     *
     * @return array
     **/
    private function statistics()
    {
        //local array with csv data and deletes 2 first lines
        $cars = $this->cars;
        unset($cars[0]);
        unset($cars[1]);

        //walks list of options, organizes array values and counts
        foreach ($this->options['list'] as $key => $val){
            $values = [];
            $statistics = [];

            foreach ($cars as $car){
                $values[] = $car[$key];
            }
            $values = array_unique($values);

            foreach ($values as $value){
                $statistics[] = $value . ' - ' . array_count_values( array_column($cars, $key) )[$value];
            }

            $list[] = [
                'name' => ucfirst($val),
                'statistics' => $statistics,
            ];

        }
        return $list;
    }
}