<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoGenerate extends Model {

    protected $primaryKey = 'id';
    protected $table = 'po_generate';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
