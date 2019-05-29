<?php

namespace App\Console\Commands;

use App\CsvFileImport;
use App\CsvFileImporter;
use App\CsvValidator;
use App\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class CSVImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csvimport:qubiz {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import large CSV File';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $file = $this->argument('file');

        $fileparts = pathinfo($file);

        $csvFileImport = CsvFileImport::where('original_filename', $fileparts['basename'])->first();

        if ($csvFileImport->status === 'processed'){
            echo 'The file was processed';
            die;
        }

        if(!$csvFileImport) {
            $csvFileImport = new CsvFileImport();
            $csvFileImport->original_filename = $fileparts['basename'];
            $csvFileImport->status = 'pending';
            $csvFileImport->row_count = 0;
            $csvFileImport->save();
        }

        $csv_importer = new CsvFileImporter();

        $csvFile = $csv_importer->moveFile($file);

        // Create validator object
        $validator = App::make('App\CsvValidator');

        $validator = $validator->validate($csvFile, $fileparts['extension']);

        if ($validator->fails()) {

            $messages = $validator->messages();
            foreach ($messages->all() as $message)
            {
                $this->info('Errors: ' . $message);
            }
        }

        $csv_importer->import($csvFile, $csvFileImport->id);

        // we start processing
        $csvFileImportProcess = CsvFileImport::where('status', 'pending')
            ->with('csvRows')
            ->first();

        $successRowsProcess = 0;
        $notProcessed = 0;

        if ($csvFileImportProcess->csvRows) {

            $header = $csvFileImportProcess->csvRows->first()->header;

            // Flip the values to keys and explode into array
            $header = array_flip(explode(',', $header));

            $notProcessedLines = '';
            $notImported = '';

            for ($i = $csvFileImportProcess->row_count ; $i < count($csvFileImportProcess->csvRows); $i++) {

                $row = $csvFileImportProcess->csvRows[$i];

                $row_array = explode(',', $row->content);

                $import_array = $header;

                foreach ($import_array as $index => $key) {
                    if (isset($row_array[$key])){
                        $import_array[$index] = $row_array[$key];
                    } else {
                        $notProcessed ++;
                        $notProcessedLines .= $row->content . "\r\n";
                        break;
                    }
                }

                $product = new Product();
                if (($import_array['cost'] > 5) && ($import_array['stock'] > 10) && ($import_array['cost'] < 1000))
                {
                    $product->code            = $import_array['code'];
                    $product->name            = $import_array['name'];
                    $product->description     = $import_array['description'];
                    $product->stock           = $import_array['stock'];
                    $product->cost            = $import_array['cost'];
                    $product->discontinued_at = null;

                    if ($import_array['discontinued']) {
                        $product->discontinued_at   = new \DateTime();
                    }
                    $product->save();
                    $successRowsProcess ++;
                } else {
                    $notImported .= $row->content . '\n';
                }
            }

            $csvFileImportProcess->row_count = $i;
            $csvFileImportProcess->status = 'processed';
            $csvFileImportProcess->save();
        }

        echo 'The number of lines that were processed ' . $csvFileImportProcess->row_count . "\r\n";
        echo 'The number of lines that were successful ' . $successRowsProcess . "\r\n";
        echo 'The number of lines that were not processed ' .$notProcessed . "\r\n";
        echo 'The not processed lines : '. "\r\n";
        echo $notProcessedLines;
    }
}
