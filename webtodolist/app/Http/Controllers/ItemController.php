<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('user')->orderBy('created_at', 'desc');

        // กรองตามประเภทโพสต์
        if ($request->has('type') && $request->type === 'mine') {
            $query->where('user_id', auth()->id());
        }

        // กรองตามสถานะ
        if ($request->has('status') && in_array($request->status, ['กำลังดำเนินการ', 'ดำเนินการเสร็จแล้ว'])) {
            $query->where('status', $request->status);
        }

        $items = $query->get();

        // ถ้าเป็น request AJAX ให้ส่งกลับเป็น JSON
        if ($request->ajax()) {
            return response()->json([
                'items' => $items
            ]);
        }

        return view('index', [
            'items' => $items,
            'currentUserId' => Auth::id()
        ]);
    }


    public function show($id)
    {
        $item = Item::with('user')->findOrFail($id);
        return view('posts.show', compact('item'));
    }

    public function filter(Request $request)
    {
        $query = Item::with('user')->orderBy('created_at', 'desc');

        if ($request->has('type') && $request->type === 'mine') {
            $query->where('user_id', auth()->user()->user_id);
        }

        if ($request->has('status') && in_array($request->status, ['กำลังดำเนินการ', 'ดำเนินการเสร็จแล้ว'])) {
            $query->where('status', $request->status);
        }

        $items = $query->get();

        return response()->json([
            'items' => $items
        ]);
    }
}
