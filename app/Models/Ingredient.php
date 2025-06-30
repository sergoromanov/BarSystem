<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'threshold',
        'unit',
    ];

    public function drinks()
    {
        return $this->belongsToMany(Drink::class, 'drink_ingredient');
    }
}
