<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ฟังก์ชันสำหรับสมัครสมาชิก (Register)
    public function register(Request $request)
    {
        // 1. ตรวจสอบข้อมูลที่ส่งมาว่าครบถ้วนไหม
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // 2. บันทึกข้อมูลลงตาราง users
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // เข้ารหัสผ่านเพื่อความปลอดภัย
            'phone_number' => $request->phone_number, // ฟิลด์พิเศษที่เราเพิ่มไว้
            'address' => $request->address,           // ฟิลด์พิเศษที่เราเพิ่มไว้
            'role' => 'customer'
        ]);

        // 3. ส่งข้อความยืนยันกลับไป
        return response()->json([
            'message' => 'สมัครสมาชิกสำเร็จเรียบร้อย!',
            'user' => $user
        ], 201);
    }

    // ฟังก์ชันสำหรับเข้าสู่ระบบ (Login)
    public function login(Request $request)
    {
        // 1. ตรวจสอบว่าส่งอีเมลและรหัสผ่านมารึเปล่า
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. ค้นหาผู้ใช้จากอีเมล
        $user = User::where('email', $request->email)->first();

        // 3. ตรวจสอบว่าเจอผู้ใช้ไหม และรหัสผ่านตรงกันหรือเปล่า
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
            ], 401);
        }

        // 4. สร้าง Token สำหรับให้ผู้ใช้เอาไปยืนยันตัวตนตอนสั่งซื้อสินค้า
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. ส่งข้อมูลและ Token กลับไป
        return response()->json([
            'message' => 'เข้าสู่ระบบสำเร็จ!',
            'user' => $user,
            'token' => $token
        ]);
    }
    // ฟังก์ชันสำหรับดูข้อมูลส่วนตัว (Member Info)
    public function profile(Request $request)
    {
        // $request->user() จะดึงข้อมูลของคนที่ถือ Token ที่ถูกต้องมาให้เลย
        return response()->json([
            'message' => 'ดึงข้อมูลโปรไฟล์สำเร็จ',
            'user' => $request->user()
        ]);
    }
}