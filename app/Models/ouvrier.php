<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ouvrier extends Authenticatable
{
    use Notifiable, HasFactory,HasApiTokens;
    protected $guarded = [];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}