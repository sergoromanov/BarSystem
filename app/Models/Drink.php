<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    protected $fillable = ['name', 'category', 'image_url'];

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'drink_ingredient')
            ->withPivot('amount');
    }

}
