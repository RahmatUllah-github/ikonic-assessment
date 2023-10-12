<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const USER = 1; // user role id
    const ADMIN = 2; // admin role id

    protected $fillable = ['name'];

    protected $timestamp = false; // not to include created_at and updated_at
}
