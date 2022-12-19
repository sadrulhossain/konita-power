<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceCommissionHistory extends Model {

    protected $primaryKey = 'id';
    protected $table = 'invoice_commission_history';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
    }

}
