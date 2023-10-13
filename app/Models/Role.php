<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    const USER = 1; // user role id
    const ADMIN = 2; // admin role id

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    public $timestamps = false; // not to include created_at and updated_at


    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
