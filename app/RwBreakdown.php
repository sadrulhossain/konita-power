<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RwBreakdown extends Model {

    protected $primaryKey = 'id';
    protected $table = 'rw_breakdown';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
