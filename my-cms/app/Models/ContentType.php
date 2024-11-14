<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    use HasFactory;

    // Define fillable properties for mass assignment
    protected $fillable = ['name', 'slug'];

    // Relationship with Field model
// In ContentType.php model
public function fields()
{
    return $this->hasMany(Field::class);
}

    
    // Relationship with Post model
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
