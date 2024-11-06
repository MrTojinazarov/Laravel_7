<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function index()
    {
        $all = Post::orderBy('id', 'desc')->paginate(10);
        $categories = Category::all();
        return view('admin.post', ['posts' => $all, 'categories' => $categories]);
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();
    
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $extension = $file->getClientOriginalExtension();
            $filename = date('Y-m-d') . '_' . time() . '.' . $extension;
            $file->move('img_uploded/', $filename);
            $data['img'] = 'img_uploded/' . $filename;
        }
    
        Post::create(array_merge($data, [
            'likes' => 0,
            'dislikes' => 0,
            'views' => 0,
        ]));
    
        return redirect()->route('post.index')->with('success', 'Post created successfully.');
    }
    

    public function show($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('views'); 
        return view('posts.show', compact('post'));
    }

    public function update(PostUpdateRequest $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->validated();
    
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $extension = $file->getClientOriginalExtension();
            $filename = date('Y-m-d') . '_' . time() . '.' . $extension;
            $file->move(public_path('img_upload'), $filename); 
            $data['img'] = 'img_upload/' . $filename; 
        } else {
            $data['img'] = $request->input('old_img');
        }
    
        $post->update($data);
    
        return redirect()->route('post.index')->with('success', 'Post updated successfully.');
    }
    


    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('post.index')->with('success', 'Post deleted successfully.');
    }

    public function like($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('likes'); 
        return back()->with('success', 'Postga like berildi.');
    }

    public function dislike($id)
    {
        $post = Post::findOrFail($id);
        $post->increment('dislikes'); 
        return back()->with('success', 'Postga dislike berildi.');
    }
}
