<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function fullName() : Attribute
    {
        return new Attribute(
            get : fn() => $this->first_name . '' . $this->last_name,
            set : fn() => ucwords($this->first_name) . '' . ucwords($this->last_name)
        );
    }

    public function scopeFilter($query, $id)
    {
        return $query->whereId($id);
    }

    public function address()
    {
         return $this->morphOne(Address::class,'addresable');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function notification()
    {
        return $this->hasManyThrough(Notification::class, Order::class);
    }
}
