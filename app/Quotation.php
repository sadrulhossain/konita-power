<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model {

    protected $primaryKey = 'id';
    protected $table = 'quotation';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
