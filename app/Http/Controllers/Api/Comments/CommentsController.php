<?php

namespace App\Http\Controllers\Api\Comments;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         // Get the current authenticated user
    $user_id = User::find(Auth::id());

    // Check if the user exists
    if (!$user_id) {
        return response()->json(['error' => 'User not authenticated'], 401);


    }

    $comments = Comment::all();
    return response()->json([
        'status' => 'success',
        'data' => $comments,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'massage' => 'required|string',
        ], [
            'massage.required' => 'The message field is required. Please enter a message.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user_id = Auth::id();

        $comment = Comment::create([
            'user_id' => $user_id,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'massage' => $request->massage,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully',
            'data' => $comment,
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $comment_id)
    {
        //
        $comment= Comment::find($comment_id);

    if (!$comment) {
        return response()->json([
            'status' => 'error',
            'massage' => 'Comment not found',
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => $comment,
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $comment_id)
    {
        //
        $comment= Comment::find($comment_id);

        if (!$comment) {
            return response()->json(data: [
                'status' => 'error',
                'massage' => 'Comment not found',
            ], status: 404);
        }

        $comment->delete();
        return response()->json([
            'status' => 'success',
            'massage' => 'Comment deleted successfully',
        ]);
}
}