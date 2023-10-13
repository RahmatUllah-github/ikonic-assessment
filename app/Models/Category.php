<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    public static $FACTORY_RECORDS_COUNT = 0; // number of records to store in the database


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    public $timestamps = false; // not to include created_at and updated_at


    public function feedbacks() :HasMany
    {
        return $this->hasMany(Feedback::class);
    }
}
