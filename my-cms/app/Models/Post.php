<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Allow mass assignment for 'content' and other relevant attributes
    protected $fillable = ['title', 'content', 'content_type_id' , 'created_by' , 'updated_by' , 'body', 'published'];

    public function contentType()
    {
        return $this->belongsTo(ContentType::class);
    }
    // In Post.php model
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    

}

