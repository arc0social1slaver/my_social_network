<?php

namespace App\Traits;

use App\Models\Friend;
use App\Models\User;

trait Friendable {
    public function add_friend($user_requested_id)
    {
        if($this->id == $user_requested_id){
            return 0;
        }
        if($this->is_friend_with($user_requested_id) == 1)
        {
            return "already friends";
        }
        if($this->has_pending_friend_request_sent_to($user_requested_id) == 1)
        {
            return "already sent a friend request";
        }
        if($this->has_pending_friend_request_from($user_requested_id) == 1)
        {
            return $this->accept_friend($user_requested_id);
        }
        $friendship = Friend::create([
            'requester' => $this->id,
            'user_requested' => $user_requested_id
        ]);
        if($friendship) {
            return 1;
        }
        return 0;
    }
    public function accept_friend($requester)
    {
        if($this->has_pending_friend_request_from($requester) == 0)
        {
            return 0;
        }
        $friendship = Friend::where('requester', $requester)->where('user_requested', $this->id)->first();
        if($friendship)
        {
            $friendship->update([
                'status' => 1
            ]);
            return 1;
        }
        return 0;
    }
    public function deny_friend($requester){
        if($this->has_pending_friend_request_from($requester) == 0)
        {
            return 0;
        }
        $friendship = Friend::where('requester', $requester)->where('user_requested', $this->id)->first();
        if($friendship)
        {
            $friendship->delete();
            return 1;
        }
        return 0;
    }
    public function delete_friend($user_requested_id){
        if($this->id == $user_requested_id)
        {
            return 0;
        }
        if($this->is_friend_with($user_requested_id) == 1)
        {
            $friendship1 = Friend::where('requester', $user_requested_id)->where('user_requested', $this->id)->first();
            if($friendship1) {
                $friendship1->delete();
            }
            $friendship2 = Friend::where('user_requested', $user_requested_id)->where('requester', $this->id)->first();
            if($friendship2)
            {
                $friendship2->delete();
            }
        }
        return 1;
    }
    public function friends() {
        $friends = array();
        $f1 = Friend::where('status', 1)->where('requester', $this->id)->get();
        foreach($f1 as $friendship):
            array_push($friends, User::find($friendship->user_requested));
        endforeach;
        $friend2 = array();
        $f2 = Friend::where('status', 1)->where('user_requested', $this->id)->get();
        foreach($f2 as $friendship):
            array_push($friend2, User::find($friendship->requester));
        endforeach;
        return array_merge($friends, $friend2);
    }
    public function pending_friend_request(){
        $users = array();
        $friendships = Friend::where('status', 0)->where('user_requested', $this->id)->get();
        foreach($friendships as $friendship):
            array_push($users, User::find($friendship->requester));
        endforeach;
        return $users;
    }
    public function friends_ids(){
        return collect($this->friends())->pluck('id')->toArray();
    }
    public function is_friend_with($id){
        if(in_array($id, $this->friends_ids())) {
            return 1;
        }
        return 0;
    }
    public function pending_friends_requests_ids(){
        return collect($this->pending_friend_request())->pluck('id')->toArray();
    }
    public function pending_friends_request_sent(){
        $users = array();
        $friendships = Friend::where('status', 0)->where('requester', $this->id)->get();
        foreach($friendships as $friendship):
            array_push($users, User::find($friendship->user_requested)->load('profile'));
        endforeach;
        return $users;
    }
    public function pending_friends_requests_sent_ids(){
        return collect($this->pending_friends_request_sent())->pluck('id')->toArray();
    }
    public function has_pending_friend_request_from($user_id)
    {
        if(in_array($user_id, $this->pending_friends_requests_ids()))
        {
            return 1;
        }
        return 0;
    }
    public function has_pending_friend_request_sent_to($user_id) {
        if(in_array($user_id, $this->pending_friends_requests_sent_ids())) {
            return 1;
        }
        return 0;
    }
}