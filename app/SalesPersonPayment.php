<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SalesPersonPayment extends Model {

    protected $primaryKey = 'id';
    protected $table = 'sales_person_payment';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
