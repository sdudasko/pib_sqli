<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'price', 'likes', 'description', 'url', 'unsplash_id', 'file_path', 'user_id'];

    protected $table = 'foods';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
