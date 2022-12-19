<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BuyerToProduct extends Model {

    protected $primaryKey = 'id';
    protected $table = 'buyer_to_product';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->created_at = date('Y-m-d H:i:s');
        });
    }

}
