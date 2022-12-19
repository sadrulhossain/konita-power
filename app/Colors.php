<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Colors extends Model {

    protected $primaryKey = 'id';
    protected $table = 'colors';
    public $timestamps = false;
    

}
