<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Item;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $file = $request->file('image');

            if (!$file->isValid()) {
                throw new \Exception('ไฟล์ไม่ถูกต้อง');
            }

            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();
            $path = 'img/' . $filename;

            // ใช้ put แทน putFileAs และปิดการใช้ ACL
            $uploaded = Storage::disk('s3')->put(
                $path,
                file_get_contents($file->getRealPath()),
                ['visibility' => null] // ไม่ใช้ ACL
            );

            if (!$uploaded) {
                throw new \Exception('อัปโหลดไฟล์ล้มเหลว');
            }

            // สร้าง URL ด้วยตนเอง
            $url = config('filesystems.disks.s3.url') . '/' . $path;

            $item = Item::create([
                'user_id' => $user->user_id,
                'username' => $user->username,
                'name' => $request->name,
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
            Log::error('Upload Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
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
