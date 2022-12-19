<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {

    protected $primaryKey = 'id';
    protected $table = 'invoice';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
