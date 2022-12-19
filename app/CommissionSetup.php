<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommissionSetup extends Model {

    protected $primaryKey = 'id';
    protected $table = 'commission_setup';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
