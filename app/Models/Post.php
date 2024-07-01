<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'parent_id', 'body'];
    protected $with = ['user', 'comments', 'images'];

    protected $appends = ['liked', 'disliked'];

    public function getLikedAttribute(){
        return $this->likes()->where('like', 1)
            ->where('likable_id', $this->id)
            ->where('likable_type', get_class($this))
            ->count();
    }
    public function getDislikedAttribute(){
        return $this->likes()->where('dislike', 1)
            ->where('likable_id', $this->id)
            ->where('likable_type', get_class($this))
            ->count();
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function scopeAllPosts($query){
        return  $query->where('user_id', auth()->id())
        ->orWhereIn('user_id', auth()->user()->friends_ids());
    }
    public function likes(){
        return $this->morphMany(Like::class, 'likable');
    }
    public function comments() {
        return $this->hasMany(Comment::class);
    }
    public function images() {
        return $this->hasMany(PostImage::class);
    }
}
