<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class CrmQuotationTerms extends Model {

    protected $primaryKey = 'id';
    protected $table = 'crm_quotation_terms';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
