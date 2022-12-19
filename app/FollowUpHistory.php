<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class FollowUpHistory extends Model {

    protected $primaryKey = 'id';
    protected $table = 'follow_up_history';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
