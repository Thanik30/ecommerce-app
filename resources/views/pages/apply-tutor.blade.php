@extends('layouts.app')

@section('title', 'สมัครและยืนยันตัวตนเป็นติวเตอร์ — EngTutor Academy')

@section('content')
<div class="max-w-2xl w-full mx-auto px-4 sm:px-6 py-10">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-5 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-amber-400">TUTOR IDENTITY VERIFICATION</span>
                <h1 class="font-bold text-lg" data-i18n="apply_tutor_title">สมัครและยืนยันตัวตนเพื่อเป็นติวเตอร์คณะวิศวกรรมศาสตร์</h1>
            </div>
        </div>

        <form onsubmit="handleTutorApplication(event)" class="p-6 sm:p-8 space-y-5 text-sm">
            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1.5">
                <p>กรอก <strong>ชื่อ-นามสกุลจริง</strong> สำหรับให้แอดมินตรวจสอบยืนยันตัวตน และตั้ง <strong>ชื่อติวเตอร์ (นามแฝง)</strong> สำหรับแสดงบนหน้าคอร์สเรียน (ข้อมูลทั้งหมดจะถูกบันทึกลงตาราง <code>users</code> ในฐานข้อมูล)</p>
                <button type="button" onclick="fillSampleTutorVerification()" class="underline font-bold text-academic-blue cursor-pointer">
                    ⚡ คลิกเพื่อกรอกข้อมูลตัวอย่างอัตโนมัติ
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อ-นามสกุลจริง *</label>
                    <input id="tutorRealName" type="text" required placeholder="เช่น นายสมชาย สายชม" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อติวเตอร์ *</label>
                    <input id="tutorAliasName" type="text" required placeholder="เช่น ม้านิลมังกร" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none font-bold text-academic-blue">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสนักศึกษา *</label>
                    <input id="tutorStudentId" type="text" required placeholder="670610xxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">สาขาวิชาที่เรียน *</label>
                    <select id="tutorDepartment" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none bg-white">
                        <option value="สาขาวิศวกรรมคอมพิวเตอร์">สาขาวิศวกรรมคอมพิวเตอร์</option>
                        <option value="สาขาวิศวกรรมโยธา">สาขาวิศวกรรมโยธา</option>
                        <option value="สาขาวิศวกรรมไฟฟ้า">สาขาวิศวกรรมไฟฟ้า</option>
                        <option value="สาขาวิศวกรรมเครื่องกล">สาขาวิศวกรรมเครื่องกล</option>
                        <option value="สาขาวิศวกรรมอุตสาหการ">สาขาวิศวกรรมอุตสาหการ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">เกรดเฉลี่ยสะสม (GPAX) *</label>
                    <input id="tutorGpax" type="number" step="0.01" min="2.00" max="4.00" required placeholder="3.65" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">เบอร์โทรศัพท์ติดต่อ *</label>
                    <input id="tutorPhone" type="text" required placeholder="089-xxx-xxxx" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ลิงก์ใบแสดงผลการเรียน (Transcript URL) *</label>
                    <input id="tutorTranscriptUrl" type="url" required placeholder="https://drive.google.com/..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">อัปโหลดรูปบัตรนักศึกษา (ไฟล์รูปภาพ)</label>
                <input id="tutorIdCardFile" type="file" accept="image/*" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs bg-slate-50 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-academic-blue file:text-white file:font-semibold cursor-pointer">
            </div>

            <div class="pt-3 flex gap-3">
                <a href="/" class="w-1/3 py-3 rounded-xl border border-slate-300 text-slate-600 font-semibold text-center">ยกเลิก</a>
                <button type="submit" class="w-2/3 py-3 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">
                    ✓ ยืนยันตัวตน
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function fillSampleTutorVerification() {
        document.getElementById('tutorRealName').value = 'ธิษณ์ธาวิน คงนา';
        document.getElementById('tutorAliasName').value = 'เสี่ยไอซ์';
        document.getElementById('tutorStudentId').value = '670610706';
        document.getElementById('tutorDepartment').value = 'สาขาวิศวกรรมคอมพิวเตอร์';
        document.getElementById('tutorGpax').value = '3.75';
        document.getElementById('tutorPhone').value = '089-456-7890';
        document.getElementById('tutorTranscriptUrl').value = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
    }

    async function handleTutorApplication(e) {
        e.preventDefault();
        if (!authToken) {
            openAuthModal('login');
            return;
        }
        const formData = new FormData();
        formData.append('real_name', document.getElementById('tutorRealName').value.trim());
        formData.append('tutor_alias', document.getElementById('tutorAliasName').value.trim());
        formData.append('student_id', document.getElementById('tutorStudentId').value.trim());
        formData.append('department', document.getElementById('tutorDepartment').value);
        formData.append('gpax', document.getElementById('tutorGpax').value);
        formData.append('phone_number', document.getElementById('tutorPhone').value.trim());
        formData.append('transcript_url', document.getElementById('tutorTranscriptUrl').value.trim());

        const fileInput = document.getElementById('tutorIdCardFile');
        if (fileInput.files.length > 0) {
            formData.append('id_card_image', fileInput.files[0]);
        }

        const res = await fetch('/api/apply-tutor', {
            method: 'POST',
            headers: { 
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}` // ส่ง Token ไปให้ API
            },
            body: formData
        });
        const data = await res.json();
        if (!res.ok) {
            showToast(data.message || 'ไม่สามารถยืนยันตัวตนได้ กรุณาตรวจสอบข้อมูล', true);
            return;
        }
        currentUser = data.user;
        localStorage.setItem('cached_user', JSON.stringify(currentUser));
        updateAuthUI();
        showToast(data.message || 'ยืนยันตัวตนสำเร็จ! กำลังพาไปหน้าลงขายคอร์สเรียน...');
        setTimeout(() => { window.location.href = '/create-course'; }, 1000);
    }
</script>
@endsection