@extends('layouts.app')

@section('title', 'แก้ไขโปรไฟล์และรูปภาพ — EngTutor Academy')

@section('content')
<div class="max-w-xl w-full mx-auto px-4 sm:px-6 py-10">
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="px-6 py-5 bg-slate-900 text-white">
            <span class="text-[11px] font-bold uppercase tracking-wider text-sky-400">MEMBER PROFILE SETTINGS</span>
            <h1 class="font-bold text-lg" data-i18n="profile_page_title">ตั้งค่าโปรไฟล์และรูปภาพของฉัน</h1>
        </div>

        <form onsubmit="handleUpdateProfile(event)" class="p-6 sm:p-8 space-y-5 text-sm">
            <!-- รูปโปรไฟล์จำค่าถาวร -->
            <div class="flex flex-col items-center space-y-3">
                <img id="profilePreviewAvatar" src="https://ui-avatars.com/api/?name=U&background=0066FF&color=fff" alt="Avatar" class="w-28 h-28 rounded-full object-cover border-4 border-blue-100 shadow-sm">
                <label class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs cursor-pointer transition">
                    <span>📷 เลือกรูปโปรไฟล์ใหม่จากเครื่อง</span>
                    <input id="profileAvatarFile" type="file" accept="image/*" onchange="previewProfileAvatar(event)" class="hidden">
                </label>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อที่ใช้แสดง / ชื่อติวเตอร์ (นามแฝง) *</label>
                <input id="profileName" type="text" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none font-semibold">
            </div>

            <div id="profileRealNameRow" class="hidden">
                <label class="block text-xs font-semibold text-slate-500 mb-1">ชื่อ-นามสกุลจริง (ข้อมูลยืนยันตัวตนในระบบ)</label>
                <input id="profileRealName" type="text" disabled class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-xs">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">สาขาวิชาที่เรียน *</label>
                <select id="profileDepartment" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none bg-white">
                    <option value="สาขาวิศวกรรมคอมพิวเตอร์">สาขาวิศวกรรมคอมพิวเตอร์</option>
                    <option value="สาขาวิศวกรรมโยธา">สาขาวิศวกรรมโยธา</option>
                    <option value="สาขาวิศวกรรมไฟฟ้า">สาขาวิศวกรรมไฟฟ้า</option>
                    <option value="สาขาวิศวกรรมเครื่องกล">สาขาวิศวกรรมเครื่องกล</option>
                    <option value="สาขาวิศวกรรมอุตสาหการ">สาขาวิศวกรรมอุตสาหการ</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">เบอร์โทรศัพท์ติดต่อ</label>
                <input id="profilePhone" type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none">
            </div>

            <div class="pt-3 flex gap-3">
                <a href="/my-courses" class="w-1/3 py-3 rounded-xl border border-slate-300 text-slate-600 font-semibold text-center">ย้อนกลับ</a>
                <button type="submit" class="w-2/3 py-3 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">
                    💾 บันทึกโปรไฟล์ลงฐานข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewProfileAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById('profilePreviewAvatar').src = URL.createObjectURL(file);
        }
    }

    async function handleUpdateProfile(e) {
        e.preventDefault();

        if (!authToken) {
            showToast('กรุณาเข้าสู่ระบบก่อนทำการบันทึกโปรไฟล์', true);
            return;
        }

        const formData = new FormData();
        formData.append('name', document.getElementById('profileName').value.trim());
        formData.append('department', document.getElementById('profileDepartment').value);
        formData.append('phone_number', document.getElementById('profilePhone').value.trim());

        const fileInput = document.getElementById('profileAvatarFile');
        if (fileInput.files.length > 0) {
            formData.append('avatar_image', fileInput.files[0]);
        }

        try {
            const res = await fetch('/api/profile/update', {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}` // ส่ง Token ไปให้ API
                },
                body: formData
            });
            const data = await res.json();
            if (!res.ok) {
                showToast(data.message || 'ไม่สามารถบันทึกโปรไฟล์ได้', true);
                return;
            }
            currentUser = data.user;
            localStorage.setItem('cached_user', JSON.stringify(currentUser));
            updateAuthUI();
            document.getElementById('profilePreviewAvatar').src = currentUser.avatar_url;
            showToast('✓ บันทึกโปรไฟล์และรูปภาพลงตาราง users เรียบร้อยแล้ว!');
        } catch (err) {
            showToast('เกิดข้อผิดพลาดในการเชื่อมต่อ', true);
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        const user = await fetchMemberProfile();
        if (!user) {
            window.location.href = '/';
            return;
        }
        document.getElementById('profilePreviewAvatar').src = user.avatar_url || 'https://ui-avatars.com/api/?name=U&background=0066FF&color=fff';
        document.getElementById('profileName').value = user.tutor_alias || user.name || '';
        document.getElementById('profilePhone').value = user.phone_number || '';
        document.getElementById('profileDepartment').value = user.department || user.address || 'สาขาวิศวกรรมคอมพิวเตอร์';

        if (user.role === 'tutor' && user.real_name) {
            document.getElementById('profileRealNameRow').classList.remove('hidden');
            document.getElementById('profileRealName').value = user.real_name;
        }
    });
</script>
@endsection