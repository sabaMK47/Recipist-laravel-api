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
    

}
