<?php

$fileManager = new FileManager();

$verbs = collect(['Behaviours', 'Characteristics', 'Things', 'Traits', 'Manners', 'Demeanors', 'Attitudes']);
$exhibit = collect(['Exhibit', 'Display', 'Portray', 'Embody', 'Possess']);
$curious = collect(['Curious', 'Bizarre', 'Strange', 'Warped', 'Odd', 'Peculiar']);

$app->get('/', function () use ($app, $fileManager, $verbs, $exhibit, $curious) {
    $lines = $fileManager->getData();

    return view('index')->with([
        'title'   => "&#127788;",
        'quote'   => $lines->random(),
        'verb'    => $verbs->random(),
        'exhibit' => $exhibit->random(),
        'curious' => $curious->random(),
        'updated' => $fileManager->getLastUpdated(),
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

