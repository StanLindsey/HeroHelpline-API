<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    public $messages;
    public $insults;
    public $heroes;

    public function __construct()
    {
        $this->heroes   = file_get_contents(storage_path('/data/heroes.json'));
        $this->messages = file_get_contents(storage_path('/data/messages.json'));
        $this->insults  = file_get_contents(storage_path('/data/insults.json'));
    }

    public function help ()
    {
        return $this->chooseHeroes();
    }

    public function chooseHeroes ()
    {
        $heroes = collect(json_decode($this->heroes));
        $heroes = json_encode($heroes->random(3));

        

        return $heroes;

        // 3 heroes
        // opening - I'll do it
        // insults - your mother...
        // exit - see you in {{time}}
    }

}
