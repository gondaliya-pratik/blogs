<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comments;
use App\Likes;
use App\User;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = Post::withCount('likes')->where('is_published',1)->orderBy('id','desc')->get();
        return view('home', compact('posts'));
    }

    public function BlogLike(Request $request) 
    {
        $like = Likes::where('post_id',$request->post_id)->where('user_id',Auth::id())->first();
        if($like) {
           $like->delete(); 
        } else {
            Likes::create([
                'post_id' => $request->post_id,
                'user_id' => Auth::id(),
            ]);
        }
        $count = Likes::where('post_id',$request->post_id)->get()->count();
        return response()->json([
            'status' => 200,
            'data' => $count
        ]);
    }

    public function BlogComment(Request $request) {

        if(!empty($request->parent_id)) {
            $comment = Comments::create([
                'user_id' => Auth::id(),
                'post_id' => $request->post_id,
                'parent_id' => $request->parent_id,
                'body' => $request->comment,
            ]);
        } else {
            $comment = Comments::create([
                'user_id' => Auth::id(),
                'post_id' => $request->post_id,
                'body' => $request->comment,
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $comment
        ]);

    }
}
