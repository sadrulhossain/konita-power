<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BuyerToGsmVolume extends Model {

    protected $primaryKey = 'id';
    protected $table = 'buyer_to_gsm_volume';
    public $timestamps = true;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->created_at = date('Y-m-d H:i:s');
			$post->updated_by = Auth::user()->id;
        });
    }

}
