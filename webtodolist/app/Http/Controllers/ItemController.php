<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('index', [
            'items' => $items,
            'currentUserId' => Auth::id()
        ]);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        if ($item->user_id != auth()->user()->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $item->delete();

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        if ($item->user_id != auth()->user()->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $item->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }
}
