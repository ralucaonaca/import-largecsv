<?php
/**
 * Created by PhpStorm.
 * User: ralucaonaca-boca
 * Date: 5/22/19
 * Time: 10:58 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class CsvRowImport extends Model
{
    protected $softDelete = false;

    protected $table = 'csvrow';

    public function csvImport()
    {
        $this->belongsTo('csvImport');
    }
}