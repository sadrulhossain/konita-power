<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class LeadFollowUp extends Model {

    protected $primaryKey = 'id';
    protected $table = 'lead_follow_up';
    public $timestamps = false;

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = Auth::user()->id;
            $post->created_at = date('Y-m-d H:i:s');
        });
    }

}
