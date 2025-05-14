<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('user')->orderBy('created_at', 'desc');

        if ($request->has('type') && $request->type === 'mine') {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('status') && in_array($request->status, ['กำลังดำเนินการ', 'ดำเนินการเสร็จแล้ว'])) {
            $query->where('status', $request->status);
        }

        $items = $query->get();

        if ($request->ajax()) {
            return response()->json(['items' => $items]);
        }

        return view('index', [
            'items' => $items,
            'currentUserId' => Auth::id()
        ]);
    }

    public function show($id)
    {
        $item = Item::with('user')->findOrFail($id);
        $isOwner = auth()->check() && (auth()->user()->user_id === $item->user_id);

        return view('posts.show', [
            'item' => $item,
            'isOwner' => $isOwner
        ]);
    }

    public function filter(Request $request)
    {
        $query = Item::with('user')->orderBy('created_at', 'desc');

        if ($request->has('type') && $request->type === 'mine') {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('status') && in_array($request->status, ['กำลังดำเนินการ', 'ดำเนินการเสร็จแล้ว'])) {
            $query->where('status', $request->status);
        }

        $items = $query->get();

        return response()->json(['items' => $items]);
    }
    public function update(Request $request, $id)
    {
        try {
            Log::info('Starting update process', [
                'item_id' => $id,
                'user_id' => auth()->id(),
                'input' => $request->all()
            ]);

            $item = Item::findOrFail($id);

            if ($item->user_id != auth()->id()) {
                throw new \Exception('Unauthorized', 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|in:กำลังดำเนินการ,ดำเนินการเสร็จแล้ว',
                'image' => 'nullable|image|max:2048'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', ['errors' => $validator->errors()]);
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->only(['name', 'description', 'status']);

            if ($request->hasFile('image')) {
                Log::info('Processing image upload');
                if ($item->image_url) {
                    $oldImagePath = parse_url($item->image_url, PHP_URL_PATH);
                    Storage::disk('s3')->delete($oldImagePath);
                }

                $file = $request->file('image');
                $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('img', $filename, 's3');
                $data['image_url'] = Storage::disk('s3')->url($path);
            }

            $item->update($data);

            Log::info('Update successful', ['item_id' => $id]);

            return redirect()->route('posts.show', $id)
                ->with('success', 'อัปเดตโพสต์เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('Update error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);

            if ($item->user_id != auth()->user()->user_id) {
                return back()->with('error', 'คุณไม่มีสิทธิ์ลบโพสต์นี้');
            }

            if ($item->image_url) {
                $oldImagePath = parse_url($item->image_url, PHP_URL_PATH);
                Storage::disk('s3')->delete($oldImagePath);
            }

            $item->delete();

            return redirect('/');
        } catch (\Exception $e) {
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}
