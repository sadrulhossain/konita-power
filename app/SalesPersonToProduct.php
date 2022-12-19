<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class SalesPersonToProduct extends Model {

    protected $primaryKey = 'id';
    protected $table = 'sales_person_to_product';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
        });
    }

}
