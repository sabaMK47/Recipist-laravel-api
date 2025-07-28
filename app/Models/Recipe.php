<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Recipe extends Model
{
    // use Searchable;
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

    protected $casts = [
        'tags' => 'array',
        'steps' => 'array',
        'nutrition' => 'array',
    ];
    
    protected $fillable = [
        'name',
        'minutes',
        'tags',
        'nutrition',
        'steps',
        'description',
    ];

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorites', 'recipe_id', 'user_id');
    }

}
