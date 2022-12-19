<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrderMessaging extends Model {

    protected $primaryKey = 'id';
    protected $table = 'order_messaging';
    public $timestamps = false;

}
