<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class UserWiseQuotationReq extends Model {

    protected $primaryKey = 'id';
    protected $table = 'user_wise_quotation_req';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }
}
