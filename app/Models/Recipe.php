<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Recipe extends Model
{
    use Searchable;
    // public function ingredients()
    // {
    //     return $this->belongsToMany(Ingredient::class);
    // }

    protected $casts = [
        'directions' => 'array',
        'NER' => 'array',
      
    ];
    
    protected $fillable = [
        'title',
        'directions',
        'NER',
        'genre',
        'label',
    ];

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'genre' => $this->genre,
        ];
    }

}
