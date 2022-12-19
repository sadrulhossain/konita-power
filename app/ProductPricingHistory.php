<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductPricingHistory extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_pricing_history';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->updated_by = Auth::user()->id;
            $post->updated_at = date('Y-m-d H:i:s');
        });
    }

}
