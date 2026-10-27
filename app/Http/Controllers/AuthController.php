<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class AuthController extends Controller
{
    private function attachUserMeta(User $user)
    {
        $defaultMeta = [
            'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0066FF&color=fff&bold=true',
            'real_name' => $user->name,
            'tutor_alias' => $user->name,
            'department' => 'สาขาวิศวกรรมคอมพิวเตอร์',
            'student_id' => '',
            'gpax' => '',
        ];

        $savedMeta = Cache::get("user_meta_{$user->id}", []);
        $user->meta = array_merge($defaultMeta, $savedMeta);
        return $user;
    }

    // 1. สมัครสมาชิก
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address ?: 'สาขาวิศวกรรมคอมพิวเตอร์',
            'role' => 'customer'
        ]);

        Cache::forever("user_meta_{$user->id}", [
            'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0066FF&color=fff&bold=true',
            'real_name' => $user->name,
            'tutor_alias' => $user->name,
            'department' => $request->address ?: 'สาขาวิศวกรรมคอมพิวเตอร์',
            'student_id' => '',
            'gpax' => '',
        ]);

        return response()->json([
            'message' => 'สมัครสมาชิกสำเร็จเรียบร้อย!',
            'user' => $this->attachUserMeta($user)
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
            'user' => $this->attachUserMeta($user),
            'token' => $token
        ]);
    }

    // 3. ดูข้อมูลโปรไฟล์
    public function profile(Request $request)
    {
        return response()->json([
            'message' => 'ดึงข้อมูลโปรไฟล์สำเร็จ',
            'user' => $this->attachUserMeta($request->user())
        ]);
    }

    // 4. แก้ไขโปรไฟล์และอัปโหลดรูปภาพโปรไฟล์
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:30',
            'department' => 'nullable|string|max:255',
            'tutor_alias' => 'nullable|string|max:255',
            'avatar_image' => 'nullable|image|max:5120',
        ]);

        $meta = Cache::get("user_meta_{$user->id}", []);

        if ($request->hasFile('avatar_image')) {
            $file = $request->file('avatar_image');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/avatars');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
            $meta['avatar_url'] = '/uploads/avatars/' . $filename;
        }

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        if ($request->department) {
            $user->address = $request->department;
            $meta['department'] = $request->department;
        }
        $user->save();

        if ($request->tutor_alias) {
            $meta['tutor_alias'] = $request->tutor_alias;
        } else {
            $meta['tutor_alias'] = $user->name;
        }

        Cache::forever("user_meta_{$user->id}", $meta);

        return response()->json([
            'message' => 'บันทึกการแก้ไขโปรไฟล์เรียบร้อยแล้ว!',
            'user' => $this->attachUserMeta($user)
        ]);
    }

    // 5. สมัครและยืนยันตัวตนเพื่อเป็นติวเตอร์ (เก็บทั้งชื่อ-นามสกุลจริง และชื่อนามแฝงติวเตอร์)
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
            'id_card_image' => 'nullable|image|max:5120',
        ]);

        $user = $request->user();
        $meta = Cache::get("user_meta_{$user->id}", []);

        if ($request->hasFile('id_card_image')) {
            $file = $request->file('id_card_image');
            $filename = 'tutor_id_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/tutors');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            $file->move($uploadPath, $filename);
        }

        $meta['real_name'] = $request->real_name;
        $meta['tutor_alias'] = $request->tutor_alias;
        $meta['department'] = $request->department;
        $meta['student_id'] = $request->student_id;
        $meta['gpax'] = $request->gpax;

        Cache::forever("user_meta_{$user->id}", $meta);

        // อัปเดตชื่อที่แสดงให้เป็นนามแฝงติวเตอร์ และเปลี่ยนสถานะเป็น tutor
        $user->name = $request->tutor_alias;
        $user->phone_number = $request->phone_number;
        $user->address = $request->department;
        $user->role = 'tutor';
        $user->save();

        return response()->json([
            'message' => 'ยืนยันตัวตนสำเร็จ! คุณได้รับสิทธิ์เป็นติวเตอร์ในชื่อ "' . $request->tutor_alias . '" เรียบร้อยแล้ว',
            'user' => $this->attachUserMeta($user)
        ]);
    }
}