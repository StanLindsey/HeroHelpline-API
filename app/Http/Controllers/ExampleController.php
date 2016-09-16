<?php

namespace App\Http\Controllers;

class ExampleController extends Controller
{
    public $conversation;
    public $messages;
    public $insults;
    public $heroes;
    public $usedInsults;

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

        $numOfInsults = rand(1,5);

        for ($x=0; $x <= $numOfInsults; $x++)
        {
            // Trade insults
            $this->insult('proposal2', $proposal2, $proposal1);
            $this->insult('proposal1', $proposal1, $proposal2);
        }          
        
        // Second offer
        $this->addMessageToConversation('proposal2', $proposal2, collect($proposal2->responses->offers)->random());

        // Return that shit!
        return response($this->conversation);

        // opening - I'll do it
        // insults - your mother...
        // exit - see you in {{time}}
    }

    public function insult ($actor, $from, $to)
    {
        $insults = collect($from->responses->insults); 
                
        if(count($insults->get($to->name))) {
            // Yes
            $insult = $insults->get($to->name, $this->insults->random());            
        } else {
            // No
            $insult = $insults->random();            
        }
        
        //$this->usedInsults->push($insult);
        $this->addMessageToConversation($actor, $from, $insult, $to);
    }

    public function addMessageToConversation ($actor, $from, $message, $to = "")
    {
        if ($to)
        {
            $message = "@" . $to->name . " " . collect($message)->first();
        }
        
        $this->conversation->push(
            [
                'actor' => $actor,
                'name' => $from->name,
                'message' => $message,
                'id' => sha1(rand(1, 9999999999999999)),
            ]
        );
    }
    
    public function chooseHeroes ()
    {
        // Get 3 random heroes
        return collect(json_decode($this->heroes))->random(3);
    }
    
    public function accept ($hero = 'binman') 
    {
           
    }

}
