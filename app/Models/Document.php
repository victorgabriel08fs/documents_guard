<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'path', 'password', 'user_id', 'user_received', 'hash'];

    public function owner()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function received()
    {
        return $this->belongsTo('App\Models\User', 'user_received');
    }
}
