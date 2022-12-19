<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BuyerMachineType extends Model {

    protected $primaryKey = 'id';
    protected $table = 'buyer_machine_type';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        
    }

}
