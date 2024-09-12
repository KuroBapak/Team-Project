<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = ['username', 'password'];

    // Matikan timestamps jika tidak ingin menggunakan created_at dan updated_at
    public $timestamps = false;
}


