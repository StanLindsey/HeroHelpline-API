<?php

$fileManager = new FileManager();

$app->get('/', function () use ($app, $fileManager) {
    $lines = $fileManager->getData();

    return view('index')->with([
        'title' => "Humans",
        'quote' => $lines->random(),
    ]);
});

$app->put('/{data}', function () use ($app, $fileManager) {

    return $fileManager->getData();
});

class FileManager {

    protected $dataFile;

    public function __construct()
    {
        $this->dataFile = storage_path('data/data.txt');
    }

    public function getData() {

        if (file_exists($this->dataFile)) {

            $lines = collect();

            foreach(file($this->dataFile, FILE_IGNORE_NEW_LINES) as $line) {
                if(!empty(trim($line))) {
                    $lines->push($line);
                }
            }

            return $lines;
        }

        return "No data file found";
    }
}

