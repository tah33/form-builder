<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormConfiguration extends Model
{
    protected $fillable = [
        'form_id', 'type', 'name', 'label', 'placeholder', 'required','order'
    ];
}
