<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = [
        'id',
        'category_id',
        'slides'
    ];

    protected $casts = [
        'slides' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
