<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ProductToBrand extends Model {

    protected $primaryKey = 'id';
    protected $table = 'product_to_brand';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->created_at = date('Y-m-d H:i:s');
        });
    }

}
