<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guard = 'admin';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    public function bidang() {
    return $this->belongsTo(Bidang::class);
}

}
