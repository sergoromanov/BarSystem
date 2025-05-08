<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'drink_id',
        'name',
        'ingredients',
    ];

    protected $casts = [
        'ingredients' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function drink()
    {
        return $this->belongsTo(Drink::class);
    }
}
