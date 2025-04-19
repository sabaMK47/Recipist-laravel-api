<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

    protected $casts = [
        'tags' => 'array',
        'nutrition' => 'array',
        'steps' => 'array',
        'ingredients' => 'array',
        'submitted' => 'date',
    ];
    
    protected $fillable = [
        'name',
        'minutes',
        'contributor_id',
        'submitted',
        'tags',
        'nutrition',
        'n_steps',
        'steps',
        'description',
        'ingredients',
        'n_ingredients',
    ];
    

}
