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
        $conversation = collect();

        // Get our heroes
        $heroes = $this->chooseHeroes();

        $proposal1 = $heroes->pop();
        $proposal2 = $heroes->pop();
        $decliner  = $heroes->pop();

        $conversation->push(
            [
                'name' => $proposal1->name,
                'message' => collect($proposal1->responses->offers)->random(),
            ]
        );

        $conversation->push(
            [
                'name' => $decliner->name,
                'message' => collect($decliner->responses->rejections)->random(),
            ]
        );

        $conversation->push(
            [
                'name' => $proposal2->name,
                'message' => collect($proposal2->responses->offers)->random(),
            ]
        );

        $conversation->push(
            [
                'name' => $proposal2->name,
                'message' => collect($proposal2->responses->insults)->pluck($proposal2->name),
            ]
        );

        return json_encode($conversation);

        // opening - I'll do it
        // insults - your mother...
        // exit - see you in {{time}}
    }

    public function getInsults () {
        
    }
    
    public function chooseHeroes ()
    {
        // Get 3 random heroes
        $heroes = collect(json_decode($this->heroes));
        return $heroes->random(3);
    }

}
