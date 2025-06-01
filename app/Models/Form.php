<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    protected $fillable = [
        'title', 'method', 'action'
    ];

    public function configs(): HasMany
    {
        return $this->hasMany(FormConfiguration::class);
    }
}
