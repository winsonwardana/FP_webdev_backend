<?php

namespace App\Http\Controllers;

use App\User;
use App\Detail;
use App\Admin;
use App\Comment;

use App\Post;

use Carbon\Traits\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Response;
use Auth;
use Illuminate\Support\Facades\Http;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllPost()
    {
        // $post = Post::all();
        $post = DB::table('posts')->get();

        $count = $post->count();
        // return response()->json($count);
        return response()->json(['message' => $count]);


    }

    public function getLastID()
    {
        $post_id = DB::table('posts')->get()->last()->post_id;
        return $post_id;

    }

    public function createPost(Request $request)
    {
        Post::create([
                'img_url' =>$request->img_url,
                'title' => $request->title,
                'description' =>  $request->description
            ]);
            return response()->json(['message' => 'Created']);

    }
    public function loadPost()
    {
        $data = DB::table('posts')
                        ->orderBy('post_id','DESC')
                        -> get();
        return response()->json($data);
    }

    public function getImgUrl($id)
    {
        $data = DB::table('posts')
                    ->where('post_id',$id)
                    -> get();
        return response()->json($data);
     
    }

    public function updatePost(Request $request,$id)
    {
        $post = [
            'img_url' => $request->img_url,
            'title' => $request->title,
            'description' => $request->description,
        ];
        Post::where('post_id', $id)->update($post);
        return response()->json(['message' => 'Created']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deletePost($id)
    {
        DB::table('posts')
         ->where('post_id',$id)
         ->delete();
         return response()->json(['message' => 'Deleted']);
    }

    /** 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('posts')
        ->orderBy('post_id','DESC')
        -> get();
        return response()->json($data);
    }

    public function updateid($id){

        $data = DB::table('posts')
        ->where('post_id',$id)
        -> get();
        return response()->json($data);
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userLogin(Request $request)
    {
        


        $users = User::all()->where('email', $request->email);
        $count = $users->count();
        if ($count == 0) {
            return response()->json([
                'message' => 'Your Credential is wrong',
                'count' => $count,
                401
                ]);
            }
            else{
            $data = DB::table('users')
                    ->where('email',$request->email)
                        -> get();
            foreach ($data as $data) {
                $hashed_pw = $data->password;
            }
            foreach ($users as $dat) {
                    
                $detail_id = $dat->detail_id;


            }
            if(Hash::check($request->password, $hashed_pw)){

        
                $data = DB::table('users')
                -> join('details','details.detail_id','=','users.detail_id')
                ->where('details.detail_id',$detail_id)
                -> get();
                foreach ($data as $dat) {
                    $first_name = $dat->first_name;
                    $user_id = $dat->user_id;

                    // Session::put('first_name',$dat->first_name);
                    // Session::put('user_id',$dat->user_id);
                }
                    return response()->json([
                    'message' => 'accepted',
                    'first_name'=>$first_name,
                    'user_id'=> $user_id
                    
                    ]);
                
            }else{
                return response()->json(['message' => 'invalid password',401]);
            }
        
                
                 
                 //return dump($data);
            }
            
    }
    
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function signUp(Request $request){
        $details= new Detail;

        $details->first_name =  $request->first_name;
        $details->save();
        $detail_ids = DB::table('details')->get()->last()->detail_id;
        
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'detail_id' => $detail_ids
        ]);
        return response()->json([$user]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminLogin(Request $request)
    {
        $admin = Admin::all()->where('username',  $request->username)->where('password',$request->password);
        $count = $admin->count();
        if ($count == 0) {
            return Redirect::to(URL::previous())->with('message', 'Invalid  Username and or Passwords');
            }
            else{
                return response()->json(['message' => 'masuk']);
    }



}

    public function detailid(Request $request){
        $post = DB::table('posts')
        ->where('post_id',$request->id)
        -> get();    
        return response()->json($post);    
    }

    public function detailcomment(Request $request){
    
    $comments = DB::table('comments')
        // ->join('users','users.user_id','=','comments.user_id')
        // ->join('details','details.detail_id','=','users.detail_id')
        ->where('comments.post_id',$request->id)
        
        ->get();
        return response()->json($comments);
         
        //dump($comments);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function comment(Request $request)
    {
        Comment::create([ 
            'user_id' =>$request->user_id,
            'post_id'=> $request->post_id,
            'comment' =>  $request->comment
        ]);
        return response()->json(['message' => $count]);

    }

  
}
