<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['id_user', 'gambar', 'hashtag', 'deskripsi', 'judul', 'avatar'];
    public function comment()
    {
        return $this->hasMany(Comment::class, 'id_posts', 'id');
    }
    public function like()
    {
        return $this->hasMany(Like::class, 'id_post', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
