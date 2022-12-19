<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class UserWiseBuyerMessage extends Model {

    protected $primaryKey = 'id';
    protected $table = 'user_wise_buyer_message';
    public $timestamps = false;

}
