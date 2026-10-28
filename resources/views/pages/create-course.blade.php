@extends('layouts.app')

@section('title', 'ลงขายคอร์สเรียนและอัปโหลดคลิปวิดีโอ — EngTutor Academy')

@section('content')
<div class="max-w-3xl w-full mx-auto px-4 sm:px-6 py-10">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-5 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">VERIFIED TUTOR STUDIO</span>
                <h1 class="font-bold text-lg" data-i18n="create_course_title">อัปโหลดคลิปวิดีโอและลงขายคอร์สเรียนใหม่</h1>
            </div>
        </div>

        <form onsubmit="handleCreateCourse(event)" class="p-6 sm:p-8 space-y-5 text-sm">
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900">
                <strong>🔒 ข้อกำหนดการลงขาย:</strong> ระบบจะใช้ชื่อผู้สอนจากโปรไฟล์ของคุณคือ <strong id="createCourseTutorPreview" class="text-academic-blue underline"></strong> โดยอัตโนมัติ และหลังจากกดวางขายแล้ว <strong>ผู้สอนจะไม่สามารถแก้ไขราคาหรือข้อมูลเองได้ (ต้องแจ้งแอดมินเป็นผู้แก้ไขให้เท่านั้น)</strong>
            </div>

            <!-- รหัสวิชา 6 หลัก (ไม่ต้องเลือกหมวดหมู่สาขาเอง) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสวิชา 6 หลัก (ระบบจัดหมวดสาขาให้อัตโนมัติ) *</label>
                    <input id="newCourseSku" type="text" required pattern="\d{6}" maxlength="6" oninput="updateAutoCategoryBadge(this.value)" placeholder="เช่น 261342 หรือ 259104" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none font-bold text-academic-blue">
                    <span id="autoDetectedCategoryText" class="text-[11px] text-emerald-700 font-medium block mt-1">พิมพ์รหัส 6 หลัก เช่น 261xxx = วิศวกรรมคอมพิวเตอร์</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">รูปแบบเนื้อหา *</label>
                    <select id="newCourseExamType" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none bg-white">
                        <option value="Midterm + Final">Midterm + Final (ครบทั้งเทอม)</option>
                        <option value="Midterm">ติวเข้มสอบ Midterm</option>
                        <option value="Final">ติวเข้มสอบ Final</option>
                        <option value="Full Course">ตะลุยโจทย์ข้อสอบเก่า & Lab</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อวิชาและชื่อคอร์สเรียน *</label>
                <input id="newCourseName" type="text" required placeholder="เช่น Database Systems (สรุปเข้ม ER Diagram และ SQL)" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none">
            </div>

            <!-- ราคาขายเดียว + ชั่วโมงคอร์สเป็นตัวเลขเท่านั้น + อายุคอร์ส -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ราคาที่ต้องการขาย (บาท) *</label>
                    <input id="newCoursePrice" type="number" required min="0" step="1" placeholder="เช่น 490" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none font-bold text-academic-blue">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ชั่วโมงคอร์ส (กรอกเฉพาะตัวเลข) *</label>
                    <div class="relative">
                        <input id="newCourseHoursNumber" type="number" required min="0.5" max="500" step="0.5" placeholder="เช่น 20" class="w-full pl-3.5 pr-16 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none font-bold">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-semibold">ชั่วโมง</span>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">อายุคอร์สเรียน *</label>
                    <select id="newCourseDuration" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none bg-white">
                        <option value="12 เดือน">12 เดือน</option>
                        <option value="6 เดือน">6 เดือน</option>
                        <option value="ตลอดชีพ">ตลอดชีพ</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">เหมาะสำหรับ *</label>
                <input id="newCourseTarget" type="text" required placeholder="เช่น นักศึกษาชั้นปีที่ 2-3 ที่เรียนวิชานี้และต้องการปูพื้นฐานเพื่อทำข้อสอบ" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">รายละเอียดคอร์สเรียน *</label>
                <textarea id="newCourseDesc" rows="2" required placeholder="อธิบายเนื้อหาที่จะสอนในคอร์สนี้..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">หัวข้อบทเรียน (ขึ้นบรรทัดใหม่ในแต่ละบท) *</label>
                <textarea id="newCourseSyllabus" rows="3" required placeholder="บทที่ 1: ปูพื้นฐานสำคัญ&#10;บทที่ 2: ตะลุยโจทย์ข้อสอบเก่า" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
            </div>

            <!-- โซนอัปโหลดไฟล์คลิปวิดีโอ + รูปปก + ลิงก์ PDF -->
            <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-200 space-y-4">
                <h3 class="font-bold text-xs uppercase tracking-wider text-academic-blue">ไฟล์สื่อการสอน (คลิปวิดีโอ + รูปปก + ลิงก์เอกสาร PDF)</h3>

                <!-- 1. อัปโหลดไฟล์คลิปวิดีโอการสอน -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-2">
                    <label class="block text-xs font-bold text-slate-800">🎬 อัปโหลดไฟล์คลิปวิดีโอการสอน (.mp4, .webm, .mov) *</label>
                    <input id="newCourseVideoFile" type="file" accept="video/mp4,video/webm,video/quicktime,video/ogg" required onchange="previewCourseVideo(event)" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-emerald-600 file:text-white file:font-semibold cursor-pointer">
                    <video id="videoPreviewPlayer" controls class="hidden w-full max-h-48 rounded-lg bg-black mt-2"></video>
                </div>

                <!-- 2. อัปโหลดไฟล์รูปปกคอร์ส -->
                <div class="bg-white p-4 rounded-xl border border-slate-200 grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                    <div class="sm:col-span-8">
                        <label class="block text-xs font-bold text-slate-800 mb-1">🖼️ อัปโหลดไฟล์รูปปกคอร์สเรียน (.jpg, .png, .webp) *</label>
                        <input id="newCourseCoverFile" type="file" accept="image/*" required onchange="previewCourseCover(event)" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-academic-blue file:text-white file:font-semibold cursor-pointer">
                    </div>
                    <div class="sm:col-span-4 flex justify-center">
                        <div class="w-28 h-20 rounded-lg border border-slate-300 bg-slate-50 overflow-hidden flex items-center justify-center text-[11px] text-slate-400">
                            <img id="coverPreviewImg" src="" class="hidden w-full h-full object-cover">
                            <span id="coverPreviewPlaceholder">ตัวอย่างปก</span>
                        </div>
                    </div>
                </div>

                <!-- 3. ลิงก์ดาวน์โหลดเอกสาร PDF -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1">📄 ลิงก์ดาวน์โหลดเอกสารการเรียน (ไฟล์ PDF เช่น Google Drive) *</label>
                    <input id="newCoursePdfUrl" type="url" required placeholder="https://drive.google.com/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white focus:border-academic-blue focus:outline-none">
                </div>
            </div>

            <div class="pt-3 flex gap-3">
                <a href="/my-courses" class="w-1/3 py-3 rounded-xl border border-slate-300 text-slate-600 font-semibold text-center">ยกเลิก</a>
                <button id="submitCourseBtn" type="submit" class="w-2/3 py-3 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">
                    🚀 อัปโหลดคลิปและวางขายคอร์สเรียน
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateAutoCategoryBadge(skuVal) {
        const prefix = skuVal.trim().substring(0, 3);
        const labelEl = document.getElementById('autoDetectedCategoryText');
        const map = {
            '259': 'วิชาแกนวิศวกรรมพื้นฐาน (259xxx)',
            '251': 'วิศวกรรมโยธา (251xxx)',
            '252': 'วิศวกรรมไฟฟ้า (252xxx)',
            '254': 'วิศวกรรมเครื่องกล (254xxx)',
            '255': 'วิศวกรรมอุตสาหการ (255xxx)',
            '261': 'วิศวกรรมคอมพิวเตอร์ (261xxx)',
            '206': 'คณิตศาสตร์วิศวกรรม (206xxx)',
        };
        if (map[prefix]) {
            labelEl.textContent = `✓ จัดเข้าหมวดหมู่: ${map[prefix]} อัตโนมัติ`;
        } else {
            labelEl.textContent = 'ระบบจะจัดเข้าหมวดวิชาแกนวิศวกรรมพื้นฐานให้อัตโนมัติ';
        }
    }

    function previewCourseCover(event) {
        const file = event.target.files[0];
        if (file) {
            const img = document.getElementById('coverPreviewImg');
            img.src = URL.createObjectURL(file);
            img.classList.remove('hidden');
            document.getElementById('coverPreviewPlaceholder').classList.add('hidden');
        }
    }

    function previewCourseVideo(event) {
        const file = event.target.files[0];
        if (file) {
            const video = document.getElementById('videoPreviewPlayer');
            video.src = URL.createObjectURL(file);
            video.classList.remove('hidden');
        }
    }

    async function handleCreateCourse(e) {
        e.preventDefault();
        const btn = document.getElementById('submitCourseBtn');
        btn.disabled = true;
        btn.textContent = 'กำลังอัปโหลดไฟล์วิดีโอและบันทึกข้อมูล...';

        const formData = new FormData();
        formData.append('sku', document.getElementById('newCourseSku').value.trim());
        formData.append('exam_type', document.getElementById('newCourseExamType').value);
        formData.append('name', document.getElementById('newCourseName').value.trim());
        formData.append('price', document.getElementById('newCoursePrice').value);
        formData.append('hours_number', document.getElementById('newCourseHoursNumber').value);
        formData.append('duration', document.getElementById('newCourseDuration').value);
        formData.append('suitable_for', document.getElementById('newCourseTarget').value.trim());
        formData.append('description', document.getElementById('newCourseDesc').value.trim());
        formData.append('syllabus', document.getElementById('newCourseSyllabus').value.trim());
        formData.append('pdf_url', document.getElementById('newCoursePdfUrl').value.trim());
        formData.append('cover_image', document.getElementById('newCourseCoverFile').files[0]);
        formData.append('course_video', document.getElementById('newCourseVideoFile').files[0]);

        try {
            const res = await fetch('/api/products', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` },
                body: formData
            });
            const data = await res.json();
            if (!res.ok) {
                showToast(data.message || 'อัปโหลดไม่สำเร็จ กรุณาเช็คขนาดไฟล์หรือสิทธิ์โฟลเดอร์บน Ubuntu', true);
                btn.disabled = false;
                btn.textContent = '🚀 อัปโหลดคลิปและวางขายคอร์สเรียน';
                return;
            }
            showToast('🎉 อัปโหลดคลิปวิดีโอและเปิดขายคอร์สเรียนสำเร็จ!');
            setTimeout(() => { window.location.href = '/my-courses'; }, 900);
        } catch (err) {
            showToast('เกิดข้อผิดพลาดในการอัปโหลด (แนะนำให้รันเซิร์ฟเวอร์ด้วย -d post_max_size=200M)', true);
            btn.disabled = false;
            btn.textContent = '🚀 อัปโหลดคลิปและวางขายคอร์สเรียน';
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        const user = await fetchMemberProfile();
        if (!user || user.role !== 'tutor') {
            window.location.href = '/apply-tutor';
            return;
        }
        document.getElementById('createCourseTutorPreview').textContent = `${user.tutor_alias || user.name} (${user.department || 'สาขาวิศวกรรมคอมพิวเตอร์'})`;
        const params = new URLSearchParams(window.location.search);
        if (params.get('sku')) {
            document.getElementById('newCourseSku').value = params.get('sku');
            updateAutoCategoryBadge(params.get('sku'));
        }
    });
</script>
@endsection