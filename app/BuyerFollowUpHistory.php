<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BuyerFollowUpHistory extends Model {

    protected $primaryKey = 'id';
    protected $table = 'buyer_follow_up_history';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
