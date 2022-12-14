<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Photos\Photo;
use App\Models\Collaborative\Comment;
use App\Http\Controllers\Controller;
use Response;

class APICommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();
        return response($comments, 200);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
      $input = $request->all();
      $rules = ['text' => 'required'];
      $validator = \Validator::make($input, $rules);
      if ($validator->fails()) {
         $messages = $validator->messages();
         return \Response::json('Error', 400);
      } else {
        $comment = ['text' => $input["text"], 'user_id' => Auth::user()->_id];
        $comment = new Comment($comment);
        $photo = Photo::find($id);
        $photo->comments()->save($comment);

        $user = Auth::user();

        return \Response::json($comment, 200);
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::find($id)->first();
        return response()->json($comment, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
