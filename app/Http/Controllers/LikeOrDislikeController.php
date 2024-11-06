<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LikeOrDislike;
use App\Models\Post;

class LikeOrDislikeController extends Controller
{
    public function like(Request $request, $postId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please log in to like the post.');
        }

        $post = Post::findOrFail($postId);

        $like = LikeOrDislike::where('post_id', $post->id)->where('user_id', Auth::id())->first();

        if ($like) {
            if ($like->value == 1) {
                $like->delete();
            } else {
                $like->value = 1;
                $like->save();
            }
        } else {
            LikeOrDislike::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'value' => 1,
            ]);
        }

        $post->increment('likes');
        
        return back(); 
    }

    public function dislike(Request $request, $postId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'Please log in to dislike the post.');
        }

        $post = Post::findOrFail($postId);

        $dislike = LikeOrDislike::where('post_id', $post->id)
                                 ->where('user_id', Auth::id())
                                 ->first();

        if ($dislike) {
            if ($dislike->value == -1) {
                $dislike->delete();
            } else {
                $dislike->value = -1;
                $dislike->save();
            }
        } else {
            LikeOrDislike::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'value' => -1,
            ]);
        }

        $post->increment('dislikes');

        return back();
    }
}
