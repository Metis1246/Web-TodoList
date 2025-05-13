<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Item;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'image' => 'required|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $filename = Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension();
            $s3Path = 'img/' . $filename;
            $uploaded = Storage::disk('s3')->putFileAs('img', $request->file('image'), $filename);

            if (!$uploaded) {
                throw new \Exception('การอัพโหลดไฟล์ล้มเหลว');
            }

            $url = Storage::disk('s3')->url($s3Path);

            $item = Item::create([
                'user_id' => $user->user_id,
                'username' => $user->username,
                'description' => $request->description,
                'image_url' => $url,
                'status' => 'กำลังดำเนินการ'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'อัปโหลดสำเร็จ',
                'item' => $item
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'เกิดข้อผิดพลาด',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:กำลังดำเนินการ,ดำเนินการเสร็จแล้ว'
        ]);

        try {
            $user = Auth::user();
            $item = Item::where('id', $id)
                ->where('user_id', $user->user_id)
                ->firstOrFail();

            $item->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'อัพเดทสถานะสำเร็จ',
                'item' => $item
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'เกิดข้อผิดพลาด',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
