<?php

$fileManager = new FileManager();

$app->get('/', function () use ($app, $fileManager) {
    $lines = $fileManager->getData();

    return view('index')->with([
        'title' => "&#127788;",
        'quote' => $lines->random(),
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

$app->put('/{data}', function () use ($app, $fileManager) {

    return $fileManager->getData();
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
            if(!empty(trim($line))) {
                $lines->push($line);
            }
        }

        return $lines;
    }
    public function getLastUpdated() {
        return \Carbon\Carbon::parse(filectime($this->dataFile));
    }
    public function getLastAccessed() {
        return \Carbon\Carbon::parse(fileatime($this->dataFile));
    }
}

