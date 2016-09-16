<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    public $conversation;
    public $messages;
    public $insults;
    public $heroes;

    public function __construct()
    {
        $this->conversation = collect();
        $this->heroes   = file_get_contents(storage_path('/data/heroes.json'));
        $this->messages = file_get_contents(storage_path('/data/messages.json'));
        $this->insults  = file_get_contents(storage_path('/data/insults.json'));
    }

    public function help ()
    {
        // Get our heroes
        $heroes = $this->chooseHeroes();

        // Pick heroes for each role
        $proposal1 = $heroes->pop();
        $proposal2 = $heroes->pop();
        $decliner  = $heroes->pop();

        // Build a conversation
        $this->addMessageToConversation($proposal1, collect($proposal1->responses->offers)->random());
        $this->addMessageToConversation($decliner,  collect($decliner->responses->rejections)->random());
        $this->addMessageToConversation($proposal2, collect($proposal2->responses->offers)->random());
        // Insult
        $this->addMessageToConversation($proposal2, collect($proposal2->responses->insults)->pluck($proposal2->name));

        // Return that shit!
        return json_encode($this->conversation);

        // opening - I'll do it
        // insults - your mother...
        // exit - see you in {{time}}
    }

    public function getInsults ()
    {
        
    }

    public function addMessageToConversation ($hero, $message)
    {
        $this->conversation->push(
            [
                'name' => $hero->name,
                'message' => collect($message),
                'id' => sha1(collect($message)->pluck($hero->name)),
            ]
        );
    }
    
    public function chooseHeroes ()
    {
        // Get 3 random heroes
        return collect(json_decode($this->heroes))->random(3);
    }

}
