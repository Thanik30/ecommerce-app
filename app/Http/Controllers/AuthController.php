<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;

class AuthController extends Controller
{
    // 1. สมัครสมาชิกใหม่
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $dept = $request->address ?: 'สาขาวิศวกรรมคอมพิวเตอร์';
        $defaultAvatar = 'https://ui-avatars.com/api/?name=' . urlencode($request->name) . '&background=0066FF&color=fff&bold=true';

        $user = User::create([
            'name' => $request->name,
            'real_name' => $request->name,
            'tutor_alias' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $dept,
            'department' => $dept,
            'avatar_url' => $defaultAvatar,
            'role' => 'customer'
        ]);

        return response()->json([
            'message' => 'สมัครสมาชิกสำเร็จเรียบร้อย!',
            'user' => $user
        ], 201);
    }

    // 2. เข้าสู่ระบบ
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'เข้าสู่ระบบสำเร็จ!',
            'user' => $user,
            'token' => $token
        ]);
    }

    // 3. ดึงข้อมูลโปรไฟล์ปัจจุบัน
    public function profile(Request $request)
    {
        return response()->json([
            'message' => 'ดึงข้อมูลโปรไฟล์สำเร็จ',
            'user' => $request->user()
        ]);
    }

    // 4. แก้ไขโปรไฟล์และอัปโหลดรูปภาพโปรไฟล์ (บันทึกลงตาราง users ถาวร)
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'department' => 'nullable|string|max:255',
            'avatar_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:10240',
        ]);

        if ($request->hasFile('avatar_image')) {
            $file = $request->file('avatar_image');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/avatars');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }
            $file->move($uploadPath, $filename);
            $user->avatar_url = '/uploads/avatars/' . $filename;
        }

        $user->name = $request->name;
        $user->tutor_alias = $request->name;
        $user->phone_number = $request->phone_number;

        if ($request->department) {
            $user->department = $request->department;
            $user->address = $request->department;
        }

        $user->save();

        // อัปเดตชื่อผู้สอนและสาขาในคอร์สเรียนที่ผู้ใช้นี้เป็นเจ้าของให้ตรงกันทันที
        Product::where('owner_email', $user->email)->update([
            'tutor_name' => $user->tutor_alias,
            'tutor_department' => $user->department
        ]);

        return response()->json([
            'message' => 'บันทึกโปรไฟล์และรูปภาพลงฐานข้อมูลเรียบร้อยแล้ว!',
            'user' => $user->fresh()
        ]);
    }

    // 5. ยืนยันตัวตนเพื่อเป็นติวเตอร์ (บันทึกลงตาราง users ถาวร)
    public function applyTutor(Request $request)
    {
        $request->validate([
            'real_name' => 'required|string|max:255',
            'tutor_alias' => 'required|string|max:255',
            'student_id' => 'required|string|min:8|max:15',
            'department' => 'required|string|max:255',
            'gpax' => 'required|numeric|min:2.00|max:4.00',
            'transcript_url' => 'required|url',
            'phone_number' => 'required|string|max:30',
            'id_card_image' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:10240',
        ]);

        $user = $request->user();

        if ($request->hasFile('id_card_image')) {
            $file = $request->file('id_card_image');
            $filename = 'tutor_id_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/tutors');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }
            $file->move($uploadPath, $filename);
            $user->id_card_url = '/uploads/tutors/' . $filename;
        }

        $user->real_name = $request->real_name;
        $user->tutor_alias = $request->tutor_alias;
        $user->name = $request->tutor_alias;
        $user->student_id = $request->student_id;
        $user->department = $request->department;
        $user->address = $request->department;
        $user->gpax = $request->gpax;
        $user->transcript_url = $request->transcript_url;
        $user->phone_number = $request->phone_number;
        $user->role = 'tutor';
        $user->save();

        return response()->json([
            'message' => 'ยืนยันตัวตนสำเร็จ! คุณได้รับสิทธิ์เป็นติวเตอร์ในชื่อ "' . $user->tutor_alias . '" เรียบร้อยแล้ว',
            'user' => $user->fresh()
        ]);
    }
}