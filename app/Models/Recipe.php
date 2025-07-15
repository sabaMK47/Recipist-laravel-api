<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

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

    // public function toSearchableArray()
    // {
    //     return [
    //         'name' => $this->name,
    //         'tags' => $this->tags,
    //     ];
    // }

}
