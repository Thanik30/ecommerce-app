<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Coupon;
use App\Models\Promotion;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();
        Cache::flush();

        // 1. บัญชีนักศึกษาทั่วไป (role = customer, มีคอร์สที่ซื้อแล้ว 1 วิชา แต่มีคลิปที่ลงขาย 0 คลิป)
        $user1 = User::create([
            'name' => 'ธนกร',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'phone_number' => '089-456-7890',
            'address' => 'สาขาวิศวกรรมคอมพิวเตอร์',
            'role' => 'customer'
        ]);

        Cache::forever("user_meta_{$user1->id}", [
            'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode('ธนกร') . '&background=0066FF&color=fff&bold=true',
            'real_name' => 'ธนกร วงศ์สวัสดิ์',
            'tutor_alias' => 'ธนกร',
            'department' => 'สาขาวิศวกรรมคอมพิวเตอร์',
            'student_id' => '670610706',
            'gpax' => '3.65',
        ]);

        // 2. บัญชีติวเตอร์ตัวอย่าง (role = tutor, ใช้ชื่อนามแฝงติวเตอร์ว่า "เสี่ยไอซ์")
        $user2 = User::create([
            'name' => 'เสี่ยไอซ์',
            'email' => 'tutor@example.com',
            'password' => Hash::make('password123'),
            'phone_number' => '081-234-5678',
            'address' => 'สาขาวิศวกรรมคอมพิวเตอร์',
            'role' => 'tutor'
        ]);

        Cache::forever("user_meta_{$user2->id}", [
            'avatar_url' => 'https://ui-avatars.com/api/?name=Ice&background=0F172A&color=fff&bold=true',
            'real_name' => 'ธิษณ์ธาวิน คงนา',
            'tutor_alias' => 'เสี่ยไอซ์',
            'department' => 'สาขาวิศวกรรมคอมพิวเตอร์',
            'student_id' => '670610706',
            'gpax' => '3.85',
        ]);

        // 3. หมวดหมู่สาขาวิชาในคณะวิศวกรรมศาสตร์ แยกตามรหัส 3 หลักแรก
        $catCore  = Category::create(['name' => 'วิชาแกนวิศวกรรมพื้นฐาน (259xxx)']);
        $catCivil = Category::create(['name' => 'วิศวกรรมโยธา (251xxx)']);
        $catEe    = Category::create(['name' => 'วิศวกรรมไฟฟ้า (252xxx)']);
        $catMech  = Category::create(['name' => 'วิศวกรรมเครื่องกล (254xxx)']);
        $catIe    = Category::create(['name' => 'วิศวกรรมอุตสาหการ (255xxx)']);
        $catCpe   = Category::create(['name' => 'วิศวกรรมคอมพิวเตอร์ (261xxx)']);
        $catMath  = Category::create(['name' => 'คณิตศาสตร์วิศวกรรม (206xxx)']);

        // 4. รายการคอร์สเรียน (ทั้งหมดเป็นของติวเตอร์ ไม่ปนกับบัญชี customer)
        $courses = [
            [
                'category_id' => $catCore->id,
                'sku' => '259104',
                'name' => 'Computer for Engineering (ปูพื้นฐาน Python & ตะลุยข้อสอบสำหรับวิศวะทุกสาขา)',
                'description' => 'คอร์สสรุปเนื้อหาสำหรับนักศึกษาคณะวิศวกรรมศาสตร์สาขาอื่นที่ไม่เคยเขียนโปรแกรมมาก่อน ปูพื้นฐานตั้งแต่การคิดเชิงตรรกะ Flowchart, ตัวแปร, If-Else, Loop (For/While), List, String และ Function พร้อมพาทำข้อสอบเก่าของคณะย้อนหลังแบบละเอียด',
                'price' => 490.00,
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=900&q=80',
                'tutor_alias' => 'เสี่ยไอซ์',
                'owner' => 'tutor@example.com',
                'meta' => [
                    'hours_num' => 18,
                    'duration' => '12 เดือน',
                    'tutor_department' => 'สาขาวิศวกรรมคอมพิวเตอร์',
                    'exam_type' => 'Midterm + Final',
                    'suitable_for' => 'นักศึกษาวิศวกรรมศาสตร์ชั้นปีที่ 1-2 ทุกสาขา (โยธา, ไฟฟ้า, เครื่องกล, อุตสาหการ ฯลฯ) ที่เรียนวิชา 259104',
                    'syllabus' => "บทที่ 1: โครงสร้างโปรแกรม การรับ-แสดงผลข้อมูล และชนิดตัวแปร\nบทที่ 2: การเขียนเงื่อนไข If-Elif-Else และการวิเคราะห์ Flowchart\nบทที่ 3: การวนซ้ำด้วย While Loop และ For Loop\nบทที่ 4: การจัดการข้อมูลด้วย List และการสร้าง Function\nบทที่ 5: ตะลุยโจทย์ข้อสอบเก่า Midterm & Final",
                    'pdf_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'
                ]
            ],
            [
                'category_id' => $catCpe->id,
                'sku' => '261102',
                'name' => 'Computer Programming (เจาะลึกโครงสร้างข้อมูล Pointer, Struct และ OOP)',
                'description' => 'เจาะลึกแนวคิดการเขียนโปรแกรมสำหรับรหัส 261xxx อธิบายการทำงานของ Memory Address, Pointer, Array, Struct และการออกแบบคลาสด้วย Object-Oriented Programming พร้อมเทคนิคการแก้โจทย์ปฏิบัติการ',
                'price' => 550.00,
                'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=900&q=80',
                'tutor_alias' => 'เสี่ยไอซ์',
                'owner' => 'tutor@example.com',
                'meta' => [
                    'hours_num' => 22,
                    'duration' => '12 เดือน',
                    'tutor_department' => 'สาขาวิศวกรรมคอมพิวเตอร์',
                    'exam_type' => 'Full Course',
                    'suitable_for' => 'นักศึกษาสาขาวิศวกรรมคอมพิวเตอร์ หรือผู้ที่ลงเรียนรหัส 261102 ที่ต้องการเข้าใจ Pointer และ OOP แบบเห็นภาพชัดเจน',
                    'syllabus' => "บทที่ 1: ทบทวน Array 2 มิติ และการจัดการ String\nบทที่ 2: เจาะลึก Memory Address และการใช้งาน Pointer\nบทที่ 3: การสร้างโครงสร้างข้อมูลด้วย Struct\nบทที่ 4: หลักการ OOP (Class, Object, Inheritance)\nบทที่ 5: เฉลยโจทย์ปฏิบัติการและแนวข้อสอบปลายภาค",
                    'pdf_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'
                ]
            ],
            [
                'category_id' => $catCpe->id,
                'sku' => '261342',
                'name' => 'Database Systems (การออกแบบ ER Diagram, SQL Queries ขั้นสูง และ Normalization)',
                'description' => 'สรุปเนื้อหาและตะลุยโจทย์วิชาระบบฐานข้อมูล ครอบคลุมการแปลงความต้องการทางธุรกิจเป็น ER Diagram, การแปลงเป็น Relational Schema, คำสั่ง SQL (Join, Group By, Having, Subquery) และการทำ Normalization (1NF-BCNF)',
                'price' => 590.00,
                'image' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?auto=format&fit=crop&w=900&q=80',
                'tutor_alias' => 'เสี่ยไอซ์',
                'owner' => 'tutor@example.com',
                'meta' => [
                    'hours_num' => 20,
                    'duration' => '12 เดือน',
                    'tutor_department' => 'สาขาวิศวกรรมคอมพิวเตอร์',
                    'exam_type' => 'Midterm + Final',
                    'suitable_for' => 'นักศึกษาชั้นปีที่ 3 ที่เรียนวิชา 261342 และต้องการเก็บคะแนนเต็มทั้งพาร์ตการออกแบบฐานข้อมูลและพาร์ตเขียนคำสั่ง SQL',
                    'syllabus' => "บทที่ 1: แนวคิดระบบฐานข้อมูล และการวาด ER Diagram\nบทที่ 2: การแปลง ER Diagram เป็นตาราง Relational Schema\nบทที่ 3: คำสั่ง SQL พื้นฐานถึงขั้นสูง (JOIN / Subquery)\nบทที่ 4: Functional Dependencies และการทำ Normalization (1NF-BCNF)\nบทที่ 5: Transaction Management และแนวข้อสอบไฟนอล",
                    'pdf_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'
                ]
            ],
            [
                'category_id' => $catCivil->id,
                'sku' => '251201',
                'name' => 'Mechanics of Materials I (กลศาสตร์วัสดุ การวิเคราะห์ความเค้น และกราฟ SFD/BMD)',
                'description' => 'ปูพื้นฐานวิชาเอกของสายวิศวกรรมโยธา (251xxx) สอนวิธีวิเคราะห์แรงภายในคาน การวาดกราฟแรงเฉือนและโมเมนต์ดัด (SFD & BMD) แบบรวดเร็ว การคำนวณ Stress-Strain และวงกลมของมอร์',
                'price' => 520.00,
                'image' => 'https://images.unsplash.com/photo-1541888946425-d09bb18086f6?auto=format&fit=crop&w=900&q=80',
                'tutor_alias' => 'พี่ก้อง โยธา',
                'owner' => 'civil_tutor@example.com',
                'meta' => [
                    'hours_num' => 24,
                    'duration' => '12 เดือน',
                    'tutor_department' => 'สาขาวิศวกรรมโยธา',
                    'exam_type' => 'Midterm + Final',
                    'suitable_for' => 'นักศึกษาวิศวกรรมโยธาและเครื่องกล ชั้นปีที่ 2 ที่ต้องการเข้าใจการวาดกราฟ SFD/BMD อย่างแม่นยำ',
                    'syllabus' => "บทที่ 1: ความเค้นตั้งฉาก (Normal Stress) และความเค้นเฉือน\nบทที่ 2: ความเครียด (Strain) และกฎของฮุก\nบทที่ 3: การบิดของเพลาหน้าตัดกลม (Torsion)\nบทที่ 4: เทคนิคการวาด Shear Force & Bending Moment Diagram\nบทที่ 5: การแปลงความเค้นด้วย Mohr's Circle",
                    'pdf_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'
                ]
            ],
            [
                'category_id' => $catEe->id,
                'sku' => '252211',
                'name' => 'Electric Circuits I (การวิเคราะห์วงจรไฟฟ้า Mesh/Node, Thevenin และวงจร AC)',
                'description' => 'สรุปขั้นตอนการตั้งสมการวงจรไฟฟ้า (252xxx) อย่างเป็นระบบ ตั้งแต่กฎของโอห์ม KCL, KVL, Mesh Analysis, Nodal Analysis, การยุบวงจร Thevenin/Norton ไปจนถึง Phasor Diagram',
                'price' => 480.00,
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=900&q=80',
                'tutor_alias' => 'พี่เอิร์ธ ไฟฟ้า',
                'owner' => 'ee_tutor@example.com',
                'meta' => [
                    'hours_num' => 20,
                    'duration' => '12 เดือน',
                    'tutor_department' => 'สาขาวิศวกรรมไฟฟ้า',
                    'exam_type' => 'Midterm + Final',
                    'suitable_for' => 'นักศึกษาวิศวกรรมไฟฟ้า วิศวกรรมคอมพิวเตอร์ และสาขาที่เรียนวิชาวงจรไฟฟ้า 252211',
                    'syllabus' => "บทที่ 1: กฎพื้นฐานของวงจรไฟฟ้า (Ohm's Law, KCL, KVL)\nบทที่ 2: การวิเคราะห์วงจรด้วย Nodal และ Mesh Analysis\nบทที่ 3: ทฤษฎีบทวงจร (Superposition, Thevenin & Norton)\nบทที่ 4: วงจร RC, RL และ RLC\nบทที่ 5: การวิเคราะห์วงจรกระแสสลับด้วย Phasor",
                    'pdf_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'
                ]
            ],
            [
                'category_id' => $catMech->id,
                'sku' => '254202',
                'name' => 'Thermodynamics I (เทอร์โมไดนามิกส์ กฎข้อที่ 1-2 การเปิดตารางไอน้ำ และวัฏจักร)',
                'description' => 'เคลียร์ทุกความสับสนในวิชาเทอร์โมไดนามิกส์ (254xxx) สอนวิธีเปิดตาราง Property Tables การประมาณค่า Interpolation และการตั้งสมการกฎข้อที่ 1 และ 2 สำหรับระบบปิดและระบบเปิด',
                'price' => 460.00,
                'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=900&q=80',
                'tutor_alias' => 'พี่ภูมิ เครื่องกล',
                'owner' => 'mech_tutor@example.com',
                'meta' => [
                    'hours_num' => 18,
                    'duration' => '12 เดือน',
                    'tutor_department' => 'สาขาวิศวกรรมเครื่องกล',
                    'exam_type' => 'Midterm + Final',
                    'suitable_for' => 'นักศึกษาสาขาวิศวกรรมเครื่องกลและอุตสาหการที่เรียนรหัส 254202',
                    'syllabus' => "บทที่ 1: สมบัติของสารบริสุทธิ์ (Pure Substance)\nบทที่ 2: เทคนิคการเปิดตารางไอน้ำและการทำ Interpolation\nบทที่ 3: กฎข้อที่ 1 ของเทอร์โมไดนามิกส์\nบทที่ 4: กฎข้อที่ 2 ของเทอร์โมไดนามิกส์\nบทที่ 5: การวิเคราะห์เอนโทรปี (Entropy)",
                    'pdf_url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf'
                ]
            ],
        ];

        $createdProducts = [];
        foreach ($courses as $c) {
            $product = Product::create([
                'category_id' => $c['category_id'],
                'sku' => $c['sku'],
                'name' => $c['name'],
                'description' => $c['description'],
                'price' => $c['price'],
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $c['image'],
            ]);

            ProductVariant::create([
                'product_id' => $product->id,
                'size' => $c['tutor_alias'] . ' | OWNER:' . $c['owner'],
                'color' => json_encode($c['meta'], JSON_UNESCAPED_UNICODE),
                'stock_quantity' => 999,
            ]);

            $createdProducts[] = $product;
        }

        // 5. สร้างประวัติการสั่งซื้อที่ชำระเงินแล้ว 1 รายการ (วิชา 259104) สำหรับ user1
        $paidOrder = Order::create([
            'user_id' => $user1->id,
            'total_amount' => 490.00,
            'status' => 'paid'
        ]);

        OrderItem::create([
            'order_id' => $paidOrder->id,
            'product_id' => $createdProducts[0]->id,
            'quantity' => 1,
            'price' => 490.00
        ]);

        // 6. สร้างคูปองส่วนลด
        Coupon::create([
            'code' => 'ENGMID20',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'min_purchase' => 300,
            'is_active' => true,
            'expiration_date' => now()->addMonths(6),
        ]);

        Coupon::create([
            'code' => 'PASSA100',
            'discount_type' => 'fixed',
            'discount_value' => 100,
            'min_purchase' => 400,
            'is_active' => true,
            'expiration_date' => now()->addMonths(6),
        ]);

        Review::create([
            'user_id' => $user1->id,
            'product_id' => $createdProducts[0]->id,
            'rating' => 5,
            'comment' => 'อธิบายเข้าใจง่ายมากครับ เอกสาร PDF ที่ให้ดาวน์โหลดสรุปโค้ดไว้ครบถ้วน ทำข้อสอบพาร์ตเขียนโปรแกรมได้เต็มเลยครับ',
        ]);

        Model::reguard();
    }
}