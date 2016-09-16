<?php

$app->get('/help', 'ExampleController@help');
$app->post('/help', 'ExampleController@help');

$fileManager = new FileManager();

$adjectives = collect(['Behaviours', 'Characteristics', 'Things', 'Traits', 'Manners', 'Dynamics', 'Tendencies', 'Natures']);
$exhibit    = collect(['Exhibit', 'Display', 'Portray', 'Embody', 'Possess', 'Express', 'Project', 'Showcase', 'Characterise']);
$curious    = collect(['Curious', 'Bizarre', 'Strange', 'Warped', 'Odd', 'Peculiar', 'Extraordinary']);
$comprehend = collect(['Comprehended', 'Predicted', 'Understood', 'Acknowledged', 'Groked', 'Apprehended', 'Discerned', 'Deciphered', 'Conceived', 'Registered', 'Perceived', 'Made out', 'Thought', 'Observed']);
$on         = collect(['On', 'Pertaining to', 'Regarding', 'On the study of', 'Observations on']);

$app->get('/', function () use ($app, $fileManager, $adjectives, $exhibit, $curious, $comprehend, $on) {
    $lines = $fileManager->getData();

    return view('index')->with([
        'title'      => "&#127788;",
        'quote'      => $lines->random(),
        'adjective'  => $adjectives->random(),
        'exhibit'    => $exhibit->random(),
        'curious'    => $curious->random(),
        'comprehend' => $comprehend->random(),
        'on'         => $on->random(),
        'updated'    => $fileManager->getLastUpdated(),
    ]);
});

$app->get('/another', function () use ($app, $fileManager) {
    $lines = $fileManager->getData();
    return $lines->random();
});

$app->get('/all', function () use ($app, $fileManager) {
    $lines = $fileManager->getData();
    return json_encode($lines);
});

class FileManager {

    protected $dataFile;

    public function __construct()
    {
        $this->dataFile = storage_path('data/data.txt');
        if ( ! file_exists($this->dataFile)) {
            return "No data file found";
        }
    }

    public function getData() {

        $lines = collect();

        foreach(file($this->dataFile, FILE_IGNORE_NEW_LINES) as $line) {
            if(!empty(trim($line) && !starts_with($line, '#'))) {
                $lines->push($line);
            }
        }

        return $lines;
    }

    public function getLastUpdated() {
        return \Carbon\Carbon::createFromTimestamp(filectime($this->dataFile))->diffForHumans();
    }

    public function getLastAccessed() {
        return \Carbon\Carbon::parse(fileatime($this->dataFile));
    }
}

