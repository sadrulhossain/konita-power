<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class ShippingLine extends Model {

    protected $primaryKey = 'id';
    protected $table = 'shipping_line';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->updated_by = Auth::user()->id;
        });

        static::updating(function($post) {
            $post->updated_by = Auth::user()->id;
        });
    }

}
