<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CrmQuotation extends Model {

    protected $primaryKey = 'id';
    protected $table = 'crm_quotation';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
