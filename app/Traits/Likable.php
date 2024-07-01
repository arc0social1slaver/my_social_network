<?php

namespace App\Traits;

trait Likable
{
    public function hasLiked($query)
    {
        return (bool) $query->likes
            ->where('like', 1)
            ->where('likable_id', $query->id)
            ->where('likable_type', get_class($query))
            ->where('user_id', $this->id)
            ->count();
    }
    public function hasDisliked($query)
    {
        return (bool) $query->likes
            ->where('dislike', 1)
            ->where('likable_id', $query->id)
            ->where('likable_type', get_class($query))
            ->where('user_id', $this->id)
            ->count();
    }
    public function changeToDislike($query)
    {
        $likes = $query->likes()->where('like', 1)->where('dislike', 0)->where('user_id', $this->id)->first();
        $likes->like = $likes->like - 1;
        $likes->dislike = $likes->dislike + 1;
        $likes->update();
    }
    public function changeToLike($query)
    {
        $likes = $query->likes()->where('like', 0)->where('dislike', 1)->where('user_id', $this->id)->first();
        $likes->like = $likes->like + 1;
        $likes->dislike = $likes->dislike - 1;
        $likes->update();
    }
    public function like($query)
    {
        if ($this->id != $query->user_id && !$this->is_friend_with($query->user_id)) {
            return redirect()->back();
        } else {
            if ($this->hasLiked($query)) {
                return redirect()->back();
            }
            if ($this->hasDisliked($query)) {
                $this->changeToLike($query);
                return redirect()->back();
            }
            else{
                $query->likes()->create([
                    'user_id' => $this->id,
                    'like' => 1,
                ]);
                return redirect()->back();
            }
        }
    }
    public function dislike($query)
    {
        if ($this->id != $query->user_id && !$this->is_friend_with($query->user_id)) {
            return redirect()->back();
        } else {
            if ($this->hasDisliked($query)) {
                return redirect()->back();
            }
            if ($this->hasLiked($query)) {
                $this->changeToDislike($query);
                return redirect()->back();
            }
            else{
                $query->likes()->create([
                    'user_id' => $this->id,
                    'dislike' => 1,
                ]);
                return redirect()->back();
            }
        }
    }
}
