<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;
class PostsController extends Controller
{  

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except'=>['index','show']]);
    }






    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       // $posts=Post::all();
      // $posts=DB::select('select * from posts');  /// to use sql query for fetching datas
       //  return Post::where('title','Post one')->get();
     //  $posts=Post::orderBy('title','desc')->take(1)->get();
       // $posts=Post::orderBy('title','desc')->get();
        $posts=Post::orderBy('created_at','desc')->paginate(10);
        return view('posts.index')->with('posts',$posts);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

        $this->validate($request,[
                'title'=>'required',
                'body'=>'required',
                'cover_image'=>'image|nullable|max:1999'


        ]);
        // Hnadle file upload
        if($request->hasFile('cover_image')){
            // Get File Name with extension
            $fileNameWithExt=$request->file( 'cover_image')->getClientOriginalName();
            // Get just file name
            $fileName=pathinfo($fileNameWithExt,PATHINFO_FILENAME);
            // Get just Extension
            $extension =$request->file('cover_image')->getClientOriginalExtension();
            //File Name to Store
            $fileNameToStore=$fileName.'_'.time().'.'.$extension;
            //upload the file
            $path=$request->file('cover_image')->storeAs('public/cover_images',$fileNameToStore);

        }
        else{
            $fileNameToStore='noimage.jpg';
        }

        // create post , inserting data inti data base

        $post=new Post;
        $post->title=$request->input('title');
        $post->body=$request->input('body');
        $post->user_id=auth()->user()->id;
        $post->cover_image=$fileNameToStore;
        $post->save();
        return redirect('/posts')->with('success','Post created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
       $posts= Post::find($id);
       return view('posts.show')->with('posts',$posts);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posts= Post::find($id);
        
        // checkmcorrct user
        if(auth()->user()->id!==$posts->user_id){
            return redirect('/posts')->with('error','unauthorized user');
        }

       return view('posts.edit')->with('posts',$posts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {       $this->validate($request,[
        'title'=>'required',
        'body'=>'required'


]);
        $posts= Post::find($id);
        $posts->title=$request->input('title');
        $posts->body=$request->input('body');
        $posts->save();
        return redirect('/posts')->with('success','Post updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $posts= Post::find($id);
        if(auth()->user()->id!==$posts->user_id){
            return redirect('/posts')->with('error','unauthorized user');
        }
        $posts->delete();
        return redirect('/posts')->with('success','Post Removed');
    }
}
