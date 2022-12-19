<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BuyerPayment extends Model {

    protected $primaryKey = 'id';
    protected $table = 'buyer_payment';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
