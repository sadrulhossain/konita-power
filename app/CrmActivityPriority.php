<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CrmActivityPriority extends Model {

    protected $primaryKey = 'id';
    protected $table = 'crm_activity_priority';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
    }

}
