<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_ar',
        'email',
        'phone',
        'address',
        'address_ar',
        'city',
        'city_ar',
        'country',
        'tax_number',
        'commercial_register',
        'logo',
        'currency',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that belong to the company.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the roles that belong to the company.
     */
    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
