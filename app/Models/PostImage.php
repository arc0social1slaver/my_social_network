<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class PostImage extends Model
{
    use HasFactory;
    protected $fillable = ['image', 'post_id'];
    // protected $with = ['post'];
    protected $appends= ['profile_photo_url'];
    public function post() {
        return $this->belongsTo(Post::class);
    }
    public function getProfilePhotoUrlAttribute()
    {
        return Storage::disk('my_img')->url($this->image);
    }
}
