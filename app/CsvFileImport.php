<?php
/**
 * Created by PhpStorm.
 * User: ralucaonaca-boca
 * Date: 5/22/19
 * Time: 10:57 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsvFileImport extends Model
{
    protected $softDelete = false;

    protected $table = 'csvimport';

    public $fillable = [
        'id',
        'original_filename',
        'status',
        'row_count'
    ];

    public function csvRows()
    {
        return $this->hasMany('App\CsvRowImport');
    }
}