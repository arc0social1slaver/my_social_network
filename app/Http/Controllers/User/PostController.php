<?php

namespace App\Http\Controllers\User;

use App\Events\SomeonePostedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostFormRequest;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostFormRequest $request)
    {
        //
        $data = $request->only(['body', 'user_id', 'parent_id']);
        if(auth()->user()->id != $data['user_id'] && !auth()->user()->is_friend_with($data['user_id']))
        {
            return redirect()->back()->withErrors(['message' => 'You must be friends first!']);
        }
        if(auth()->user()->id != $data['user_id'] && auth()->user()->is_friend_with($data['user_id']))
        {
            $newPost = Post::create([
                'body' => $data['body'],
                'parent_id' => $data['user_id'],
                'user_id' => auth()->user()->id,
            ]);
            if($request->has('imgs'))
            {
                foreach($request->imgs as $img) {
                    $newHashName = $img->hashName();
                    $img->move(public_path('images'), $newHashName);
                    PostImage::create([
                        'image' => $newHashName,
                        'post_id' => $newPost->id,
                    ]);
                }
            }
            $user = User::where('id', $data['user_id'])->first();
            event(new SomeonePostedEvent($user, auth()->user()));
        }
        if(auth()->user()->id == $data['user_id'])
        {
            $newPost = auth()->user()->posts()->create([
                'body' => $data['body'],
            ]);
            if($request->has('imgs'))
            {
                foreach($request->imgs as $img) {
                    $newHashName = $img->hashName();  
                    $img->move(public_path('images'), $newHashName);
                    PostImage::create([
                        'image' => $newHashName,
                        'post_id' => $newPost->id,
                    ]);
                }
            }
            // $user = User::where('id', $data['user_id'])->first();
            // event(new SomeonePostedEvent($user, auth()->user()));
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
        if(auth()->user()->id != $post->user_id && !(auth()->user()->is_friend_with($post->user_id))) {
            return redirect()->back()->withErrors([
                'message' => 'You do not have permission to delet this post!'
            ]);
        }
        if(auth()->user()->id != $post->user_id && auth()->user()->id != $post->parent_id) {
            return redirect()->back()->withErrors([
                'message' => 'You do not have permission to delet this post!'
            ]);
        }
        if((auth()->user()->id != $post->user_id && auth()->user()->id == $post->parent_id) || auth()->user()->id == $post->user_id) {
            $postImgs = $post->images()->get();
            if($postImgs) {
                foreach($postImgs as $img) {
                    $img_name = $img->image;
                    if($img->delete()) {
                        $image_path = public_path('images').'/'.$img_name;
                        if(file_exists($image_path)) {
                            unlink($image_path);
                        }
                    }
                }
            }
            $post->delete();
        }
        return redirect()->back();
    }
}
