<?php
/**
 * Created by PhpStorm.
 * User: ralucaonaca-boca
 * Date: 5/9/19
 * Time: 3:39 PM
 */

namespace App;

use Illuminate\Support\Facades\DB;

class CsvFileImporter
{
    /**
     * Import method used for saving file and importing it using a database query
     *
     * @param Csv File
     * @return int number of lines imported
     */
    public function import($file, $csvImportId)
    {
        $fileName = $this->moveFile($file);

        $csvHeader =  ['code', 'name', 'description', 'stock', 'cost', 'discontinued'];

        // Import contents of the file into database
        return $this->importFileContents($fileName, $csvImportId, $csvHeader);
    }

    /**
     * Import CSV file into Database using LOAD DATA LOCAL INFILE function
     *
     * NOTE: PDO settings must have attribute PDO::MYSQL_ATTR_LOCAL_INFILE => true
     *
     * @param $file
     * @return mixed Will return number of lines imported by the query
     */
    private function importFileContents($file, $csvImportId, $csvHeader)
    {
        $query = "LOAD DATA LOCAL INFILE '" . $file . "' INTO TABLE csvrow 
            LINES TERMINATED BY '\\n'
            IGNORE 1 LINES
            (content)
            SET csv_file_import_id = '" . $csvImportId . "', header = '" . implode(',',$csvHeader) . "' ";

        $pdo = DB::connection()->getpdo();

        return $pdo->exec($query);
    }

    public function moveFile($file)
    {
        $fileparts = pathinfo($file);

        $destination_directory = base_path() . '/imports';
        if (is_dir($destination_directory)) {
            chmod($destination_directory, 0755);
        } else {
            mkdir($destination_directory, 0755, true);
        }

        $fileName = $destination_directory . '/' . $fileparts['basename'];
        $fileResource = fopen($file, 'r');

        if (file_put_contents($fileName, $fileResource)) {
            return $fileName;
        }

        return false;
    }
}