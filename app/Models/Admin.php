<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'authenticator_secret',
        'authenticator_enabled_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'authenticator_secret',
    ];

    protected $casts = [
        'authenticator_secret' => 'encrypted',
        'authenticator_enabled_at' => 'datetime',
    ];

    public function hasAuthenticatorEnabled(): bool
    {
        return !empty($this->authenticator_secret)
            && !empty($this->authenticator_enabled_at);
    }
}