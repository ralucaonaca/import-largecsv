<?php
/**
 * Created by PhpStorm.
 * User: ralucaonaca-boca
 * Date: 5/9/19
 * Time: 1:41 PM
 */

namespace App;

use Illuminate\Validation\Factory;

class CsvValidator
{

    /**
     * Validator object
     * @var \Illuminate\Validation\Factory
     */
    private $validator;

    /**
     * Validation rules for CsvImport
     *
     */

    private $privateHeaderValues = ['code', 'name', 'description', 'stock', 'cost', 'discontinued'];

    private $rules = [
        'csv_extension'            => 'in:csv',
        'code_column'              => 'required',
        'name_column'              => 'required',
        'description_column'       => 'required',
        'code'                     => 'required|max:10',
        'name'                     => 'required|max:50',
        'description'              => 'required|max:255',
    ];

    /**
     * Constructor for CsvValidator
     * @param \Illuminate\Validation\Factory $validator
     */
    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Validation method
     * @return \Illuminate\Validation\Validator $validator
     */
    public function validate($file, $csvExtention = 'csv')
    {

        // Line endings fix
        ini_set('auto_detect_line_endings', true);

        $header = $this->manipulateHeader($file);
        // Find code column
        $code_column = $this->getColumnNameByValue($header, 'code');

        // Find name column
        $name_column = $this->getColumnNameByValue($header, 'name');

        // Find description column
        $description_column = $this->getColumnNameByValue($header, 'description');

        // Get second row of the file as the first data row
        $data_row = fgetcsv($file, 0, ',');

        // Combine header and first row data
        $first_row = array_combine($header, $data_row);

        // Find code in the code column
        $first_row_code = array_key_exists('code', $first_row)? $first_row['code'] : '';

        // Find name in name column
        $first_row_name = array_key_exists('name', $first_row)? $first_row['name'] : '';


        // Find description in description column
        $first_row_description = array_key_exists('description', $first_row)? $first_row['description'] : '';

        // Close file and free up memory
        fclose($file);

        // Build our validation array
        $validation_array = [
            'csv_extension'         => $csvExtention,
            'code_column'           => $code_column,
            'name_column'           => $name_column,
            'description_column'    => $description_column,
            'code'                  => $first_row_code,
            'name'                  => $first_row_name,
            'description'           => $first_row_description
        ];

        // Return validator object
        return $this->validator->make($validation_array, $this->rules);
    }

    /**
     * Attempts to find a value in array or returns empty string
     * @param array  $array hay stack we are searching
     * @param string $key
     *
     */
    private function getColumnNameByValue($array, $value)
    {
        return in_array($value, $array)? $value : '';
    }

    private function manipulateHeader($file){

        $header = fgetcsv($file, 0, ',');

        $header = array_map('strtolower', $header);

        $array = [];

        foreach($header as $key => $arr_value) {
            foreach ($this->privateHeaderValues as $value){
                $pos = strpos($arr_value, $value);
                if (false !== $pos) {
                    $array[$key] = $value;
                }
            }

        }
        return $array;
    }
}