<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PiGenerate extends Model {

    protected $primaryKey = 'id';
    protected $table = 'pi_generate';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
