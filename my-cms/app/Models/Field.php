<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name', 'field_type', 'required' ,"attachment_subtype","allowed_extensions","allowed_image_extensions" , "allowed_mbs"];

    // Define the relationship with ContentType
    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }
}

