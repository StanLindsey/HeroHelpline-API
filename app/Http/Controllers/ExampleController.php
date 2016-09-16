<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

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
        $this->insults  = collect(file_get_contents(storage_path('/data/insults.json')));
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

        // Initial proposal
        $this->addMessageToConversation('proposal1', $proposal1, collect($proposal1->responses->offers)->random());

        // Decline the offer
        $this->addMessageToConversation('decliner', $decliner,  collect($decliner->responses->rejections)->random());

        // Does the proposal2 have a specific insult for proposal1?
        if(count(collect($proposal2->responses->insults)->get($proposal1->name))) {
            // Yes
            $this->addMessageToConversation('proposal2', $proposal2, collect($proposal2->responses->insults)->get($proposal1->name, $this->insults->random()));
        } else {
            // No
            $this->addMessageToConversation('proposal2', $proposal2, collect($proposal2->responses->insults)->random());
        }

        // Second offer
        $this->addMessageToConversation('proposal2', $proposal2, collect($proposal2->responses->offers)->random());

        // Return that shit!
        return response($this->conversation);

        // opening - I'll do it
        // insults - your mother...
        // exit - see you in {{time}}
    }

    public function getInsults ()
    {
        
    }

    public function addMessageToConversation ($actor, $hero, $message)
    {
        $this->conversation->push(
            [
                'actor' => $actor,
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
