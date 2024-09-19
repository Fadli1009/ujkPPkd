<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('comments', $filename, 'public');
        }
        Comment::create([
            'id_user' => $request->id_user,
            'id_posts' => $request->id_posts,
            'comments' => $request->comments,
            'file' => $path ?? ''
        ]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id_comment' => 'required|exists:comments,id',
            'comments' => 'required|string|max:255',
            'file' => 'nullable|image|max:2048',
        ]);

        $id = $request->input('id_comment');
        $comment = Comment::findOrFail($id);
        $path = $comment->file; // Preserve the existing file path

        // Handle file upload
        if ($request->hasFile('file')) {
            // Optionally delete the old file if it exists
            if ($comment->file) {
                Storage::disk('public')->delete($comment->file);
            }
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('comments', $filename, 'public');
        }

        $comment->update([
            'comments' => $request->comments,
            'file' => $path,
        ]);

        return redirect()->back()->with('success', 'Comment updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->back();
    }
}
