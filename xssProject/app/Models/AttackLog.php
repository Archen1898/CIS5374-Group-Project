<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;

class AttackLog extends Model
{
    use Uuid;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'user_input',
        'occured_at',
    ];
}
