<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $connection = 'mysql';
    protected $table = 'zona';
    protected $primaryKey = 'id_zona';
    public $incrementing = true;
    public $timestamps = false;
}
