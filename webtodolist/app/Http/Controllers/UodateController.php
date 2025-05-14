<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemService
{
    public function updateItem(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        if ($item->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:กำลังดำเนินการ,ดำเนินการเสร็จแล้ว',
            'image' => 'nullable|image|max:2048'
        ]);

        try {
            $data = $request->only(['name', 'description', 'status']);

            if ($request->hasFile('image')) {
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

            return [
                'success' => true,
                'item' => $item
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล',
                'error' => $e->getMessage()
            ];
        }
    }
}
