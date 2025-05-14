<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'content' => 'required_without:image|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = [
            'user_id' => Auth::user()->user_id,
            'item_id' => $item->id,
            'content' => $request->content
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('comment_images', $filename, 's3');
            $data['image_url'] = Storage::disk('s3')->url($path);
        }

        Comment::create($data);

        return back();
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'content' => 'required_without:image|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = ['content' => $request->content];

        if ($request->hasFile('image')) {

            if ($comment->image_url) {
                $oldImagePath = parse_url($comment->image_url, PHP_URL_PATH);
                Storage::disk('s3')->delete($oldImagePath);
            }

            $file = $request->file('image');
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('comment_images', $filename, 's3');
            $data['image_url'] = Storage::disk('s3')->url($path);
        }

        $comment->update($data);

        return back();
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }


        if ($comment->image_url) {
            $oldImagePath = parse_url($comment->image_url, PHP_URL_PATH);
            Storage::disk('s3')->delete($oldImagePath);
        }

        $comment->delete();

        return back();
    }
}
