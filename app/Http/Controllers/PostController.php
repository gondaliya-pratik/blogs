<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Post;
use App\User;
use DataTables;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        if ($request->file('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path().'/upload/post/' ;
            $image->move($destinationPath,$imageName);
        }
        
        Post::create([
        	'user_id' => Auth::id(),
        	'title' => $request->title,
        	'description' => $request->description,
        	'image' => '/upload/post/'.$imageName,
        	'is_published' => ($request->is_published == 1) ? 1 : 0
        ]);
 
        return redirect()->route('post.index')->with('success', 'Post Added Succuessfully!');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
        	$posts = Post::where('user_id',Auth::id())->orderBy('id','desc')->get();
          
            return Datatables::of($posts)->addIndexColumn()
            	->addColumn('is_published', function($row) {
            		$is_published = ($row->is_published == '1') ? 'Published' : 'Un-Published';
            		return $is_published;
            	})
            	->addColumn('publish_date', function($row) {
            		return date("d M, Y", strtotime($row->publish_date));
            	})
                ->addColumn('total_like', function($row) {
                    return $row->likes->count();
                })
                ->addColumn('total_comment', function($row) {
                    return $row->total_comments_count->count();
                })
                ->addColumn('action', function($row){
                    $btn = '<a class="btn btn-success" href="'.url('/posts/'.$row->id.'/edit').'" role="button">Edit</a>';
                    $btn .= '<button type="button" class="deletebtn btn btn-danger ml-2" data-toggle="modal" data-target="#deletePost" onclick=getPostId('.$row->id.')>Delete</button>';
                    return $btn;
                })
                ->rawColumns(['publish_date','is_published','total_like','total_comment','action'])
                ->make(true);
        }
        return view('posts.index');
    }

    public function edit($postId)
    {
    	$post = Post::where('id',$postId)->first();
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, $postId)
    {
        $data = [
        	'user_id' => Auth::id(),
        	'title' => $request->title,
        	'description' => $request->description,
        	'is_published' => ($request->is_published == 1) ? 1 : 0
        ]; 
    	if ($request->file('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path().'/upload/post/' ;
            $image->move($destinationPath,$imageName);
            $data['image'] = '/upload/post/'.$imageName;
        }

    	Post::where('id',$postId)->update($data);

        return redirect()->route('post.index')->with('success', 'Post Updated Succuessfully!');
    }

    public function destroy(Request $request)
    {
        $post = Post::where('id',$request->post_id)->first();
        if($post) {
        	$post->delete();
            return response()->json([
                'status' => 200,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong!' 
            ]);
        }
    }
}
