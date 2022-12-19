<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CrmActivityLog extends Model {

    protected $primaryKey = 'id';
    protected $table = 'crm_activity_log';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
