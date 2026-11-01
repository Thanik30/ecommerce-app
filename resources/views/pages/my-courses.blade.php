@extends('layouts.app')

@section('title', 'คอร์สของฉัน & ห้องเรียนวิดีโอ — EngTutor Academy')

@section('content')
<div class="max-w-6xl w-full mx-auto px-4 sm:px-6 py-8 space-y-6">

    <!-- หัวข้อและโปรไฟล์ย่อ -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-center gap-4">
            <img id="myCoursesAvatar" src="https://ui-avatars.com/api/?name=U&background=0066FF&color=fff" alt="Profile" class="w-14 h-14 rounded-full object-cover border-2 border-academic-blue">
            <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 id="myCoursesUserName" class="text-lg sm:text-xl font-bold text-slate-900"></h1>
                    <span id="myCoursesRoleBadge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700"></span>
                </div>
                <p id="myCoursesUserDept" class="text-xs sm:text-sm text-slate-500"></p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <a href="/profile" class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm transition cursor-pointer" data-i18n="btn_edit_profile">
                ⚙️ แก้ไขโปรไฟล์ / รูปภาพ
            </a>
            <a id="myCoursesActionBtn" href="/apply-tutor" class="px-4 py-2 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs sm:text-sm transition cursor-pointer">
                🎓 สมัครเป็นติวเตอร์
            </a>
        </div>
    </div>

    <!-- แท็บสลับ -->
    <div class="border-b border-slate-200 flex items-center gap-6 overflow-x-auto">
        <button id="subTabPurchased" onclick="switchSubTab('purchased')" class="whitespace-nowrap pb-3 border-b-2 border-academic-blue text-academic-blue font-bold text-sm sm:text-base transition cursor-pointer">
            📚 คอร์สที่ฉันซื้อแล้ว (0)
        </button>
        <button id="subTabUploaded" onclick="switchSubTab('uploaded')" class="whitespace-nowrap pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-medium text-sm sm:text-base transition cursor-pointer">
            📹 คลิปสอนที่ฉันลงขาย (0)
        </button>
    </div>

    <!-- Panel คอร์สที่ซื้อแล้ว -->
    <div id="purchasedCoursesPanel" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
    
    <!-- Panel คลิปที่ลงขาย (สำหรับติวเตอร์) -->
    <div id="uploadedCoursesPanel" class="hidden flex-col gap-6">
        <div id="tutorCouponSection" class="hidden bg-emerald-50 border border-emerald-200 p-5 rounded-xl">
            <h3 class="font-bold text-emerald-800 text-sm mb-3">🎟️ สร้างโค้ดส่วนลดสำหรับคอร์สของคุณ</h3>
            <form onsubmit="handleCreateTutorCoupon(event)" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสโค้ด *</label>
                    <input id="tutorCouponCode" type="text" required placeholder="เช่น MYCODE20" class="w-32 px-3 py-2 rounded-lg border border-slate-300 text-sm uppercase focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ประเภท *</label>
                    <select id="tutorCouponType" class="w-28 px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-emerald-500">
                        <option value="percentage">ลด %</option>
                        <option value="fixed">ลดบาท</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">มูลค่าที่ลด *</label>
                    <input id="tutorCouponValue" type="number" required placeholder="เช่น 20" class="w-28 px-3 py-2 rounded-lg border border-slate-300 text-sm focus:outline-none focus:border-emerald-500">
                </div>
                <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm cursor-pointer transition">สร้างโค้ด</button>
            </form>
        </div>
        <div id="uploadedCoursesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>
</div>

<!-- MODAL: ห้องเรียนวิดีโอแบบใหม่ (แบ่งจอ 75/25 มีแถบ Playlist ด้านขวา) -->
<div id="videoPlayerModal" class="fixed inset-0 z-50 hidden bg-slate-900/90 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-5xl w-full overflow-hidden flex flex-col md:flex-row h-[85vh] shadow-2xl relative">
        <button onclick="closeVideoPlayerModal()" class="absolute top-4 left-4 z-10 w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold cursor-pointer transition border border-slate-600 hover:bg-slate-700">✕</button>
        
        <!-- ฝั่งซ้าย: วิดีโอหลัก -->
        <div class="md:w-3/4 bg-black flex flex-col justify-center relative">
            <video id="realCourseVideoElement" controls class="w-full h-full object-contain bg-black">
                <source id="realCourseVideoSource" src="" type="video/mp4">
                เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
            </video>
        </div>

        <!-- ฝั่งขวา: รายชื่อบทเรียน (Playlist) -->
        <div class="md:w-1/4 bg-slate-50 flex flex-col border-l border-slate-200">
            <div class="p-4 bg-white border-b border-slate-200 shadow-sm">
                <h3 id="playerCourseTitle" class="font-bold text-sm text-slate-800 line-clamp-2"></h3>
                <a id="playerPdfDownloadAnchor" href="#" target="_blank" class="mt-3 block w-full py-2 text-center rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white text-xs font-semibold transition cursor-pointer">📥 โหลดเอกสาร PDF</a>
            </div>
            <div id="playlistContainer" class="flex-1 overflow-y-auto p-2 space-y-1">
                <!-- รายชื่อบทเรียนจะถูกแทรกที่นี่ด้วย JS -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let allCourses = [];
    let purchasedCourses = [];

    // ดึงและแกะข้อมูล Meta ของคอร์ส
    function parseCourseMeta(course) {
        const v = (course.variants && course.variants.length > 0) ? course.variants[0] : null;
        let parsedVariant = {};
        if (v && v.color) {
            try { parsedVariant = JSON.parse(v.color); } catch (e) {}
        }

        const tutorName = course.tutor_name || (v && v.size ? v.size.split('| OWNER:')[0].trim() : 'ติวเตอร์');
        const ownerEmail = course.owner_email || (v && v.size && v.size.includes('| OWNER:') ? v.size.split('| OWNER:')[1].trim() : '');
        const pdfUrl = course.pdf_url || parsedVariant.pdf_url || '#';
        const videoUrl = course.video_url || parsedVariant.video_url || 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';

        return { tutorName, ownerEmail, pdfUrl, videoUrl };
    }

    async function loadMyCoursesPage() {
        if (!authToken) {
            window.location.href = '/';
            return;
        }
        await fetchMemberProfile(); // โหลดโปรไฟล์จาก app.blade.php

        if (currentUser) {
            const displayName = currentUser.tutor_alias || currentUser.name;
            const avatarUrl = currentUser.avatar_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(displayName) + '&background=0066FF&color=fff&bold=true');
            document.getElementById('myCoursesAvatar').src = avatarUrl;
            document.getElementById('myCoursesUserName').textContent = displayName;
            document.getElementById('myCoursesUserDept').textContent = `${currentUser.department || currentUser.address || ''} • ${currentUser.email}`;

            const roleBadge = document.getElementById('myCoursesRoleBadge');
            const actionBtn = document.getElementById('myCoursesActionBtn');
            if (currentUser.role === 'tutor') {
                roleBadge.textContent = `✓ ติวเตอร์ (ชื่อจริง: ${currentUser.real_name || currentUser.name})`;
                roleBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800';
                actionBtn.textContent = '+ ลงขายคอร์สเรียนใหม่';
                actionBtn.href = '/create-course';
            } else {
                roleBadge.textContent = 'สถานะ: นักศึกษาทั่วไป';
                actionBtn.textContent = '🎓 สมัครเป็นติวเตอร์';
                actionBtn.href = '/apply-tutor';
            }
        }

        const [prodRes, myRes] = await Promise.all([
            fetch('/api/products', { headers: { 'Accept': 'application/json' } }),
            fetch('/api/my-courses', { headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` } })
        ]);

        const prodJson = await prodRes.json();
        allCourses = prodJson.data || [];

        if (myRes.ok) {
            const myJson = await myRes.json();
            purchasedCourses = myJson.purchased_courses || [];
        }

        renderPanels();
    }

    function switchSubTab(tab) {
        const btnPurchased = document.getElementById('subTabPurchased');
        const btnUploaded = document.getElementById('subTabUploaded');
        const panelPurchased = document.getElementById('purchasedCoursesPanel');
        const panelUploaded = document.getElementById('uploadedCoursesPanel');

        if (tab === 'purchased') {
            btnPurchased.className = 'whitespace-nowrap pb-3 border-b-2 border-academic-blue text-academic-blue font-bold text-sm sm:text-base transition cursor-pointer';
            btnUploaded.className = 'whitespace-nowrap pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-medium text-sm sm:text-base transition cursor-pointer';
            panelPurchased.classList.remove('hidden');
            panelPurchased.classList.add('grid');
            panelUploaded.classList.add('hidden');
            panelUploaded.classList.remove('flex');
        } else {
            btnUploaded.className = 'whitespace-nowrap pb-3 border-b-2 border-academic-blue text-academic-blue font-bold text-sm sm:text-base transition cursor-pointer';
            btnPurchased.className = 'whitespace-nowrap pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-medium text-sm sm:text-base transition cursor-pointer';
            panelUploaded.classList.remove('hidden');
            panelUploaded.classList.add('flex');
            panelPurchased.classList.add('hidden');
            panelPurchased.classList.remove('grid');
        }
    }

    function renderPanels() {
        const panelPurchased = document.getElementById('purchasedCoursesPanel');
        const panelUploaded = document.getElementById('uploadedCoursesPanel');
        const uGrid = document.getElementById('uploadedCoursesGrid');
        const tutorSection = document.getElementById('tutorCouponSection');

        const uploadedCourses = (currentUser && currentUser.role === 'tutor')
            ? allCourses.filter(c => parseCourseMeta(c).ownerEmail === currentUser.email)
            : [];

        document.getElementById('subTabPurchased').textContent = `📚 คอร์สที่ฉันซื้อแล้ว (${purchasedCourses.length})`;
        document.getElementById('subTabUploaded').textContent = `📹 คลิปสอนที่ฉันลงขาย (${uploadedCourses.length})`;

        // แสดงคอร์สที่ซื้อแล้ว
        if (purchasedCourses.length === 0) {
            panelPurchased.innerHTML = `
                <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 space-y-3 shadow-sm">
                    <p class="text-base font-bold text-slate-800">คุณยังไม่มีคอร์สเรียนที่ซื้อไว้</p>
                    <a href="/" class="inline-block px-5 py-2.5 rounded-xl bg-academic-blue text-white text-sm font-semibold">ไปหน้าค้นหาคอร์สเรียน</a>
                </div>
            `;
        } else {
            panelPurchased.innerHTML = purchasedCourses.map(course => {
                const imgUrl = (course.images && course.images.length > 0) ? course.images[0].image_url : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';
                const meta = parseCourseMeta(course);
                return `
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between shadow-xs">
                        <div class="flex items-start gap-4">
                            <img src="${imgUrl}" class="w-24 h-24 rounded-xl object-cover shrink-0 border border-slate-200">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded bg-blue-50 text-academic-blue font-bold text-xs">รหัส ${course.sku}</span>
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold text-[11px]">✓ ซื้อแล้ว</span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base line-clamp-2">${course.name}</h3>
                                <p class="text-xs text-slate-500">ผู้สอน: ${meta.tutorName}</p>
                            </div>
                        </div>
                        <div class="flex sm:flex-col gap-2 w-full sm:w-auto shrink-0">
                            <button onclick="openVideoPlayerModal(${course.id})" class="flex-1 sm:w-40 py-2.5 px-4 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs text-center transition cursor-pointer">
                                ▶ เข้าเรียนวิดีโอ
                            </button>
                            <a href="${meta.pdfUrl}" target="_blank" class="flex-1 sm:w-40 py-2 px-4 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs text-center transition">
                                📄 ดาวน์โหลด PDF
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // แสดงคลิปที่ลงขาย
        if (!currentUser || currentUser.role !== 'tutor') {
            tutorSection.classList.add('hidden');
            uGrid.innerHTML = `
                <div class="col-span-full bg-amber-50/70 rounded-2xl p-10 text-center border border-amber-200 space-y-3">
                    <p class="text-base font-bold text-slate-900">บัญชีของคุณยังไม่ได้ยืนยันตัวตนเป็นติวเตอร์</p>
                    <a href="/apply-tutor" class="inline-block px-6 py-2.5 rounded-xl bg-academic-blue text-white text-xs sm:text-sm font-semibold">🎓 สมัครและยืนยันตัวตนเป็นติวเตอร์</a>
                </div>
            `;
        } else {
            tutorSection.classList.remove('hidden');
            if (uploadedCourses.length === 0) {
                uGrid.innerHTML = `
                    <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 space-y-3 shadow-sm">
                        <p class="text-base font-bold text-slate-800">คุณยังไม่มีคอร์สเรียนที่เปิดขาย</p>
                        <a href="/create-course" class="inline-block px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-semibold">+ ลงขายคอร์สเรียนวิชาแรกของคุณ</a>
                    </div>
                `;
            } else {
                uGrid.innerHTML = uploadedCourses.map(course => {
                    const imgUrl = (course.images && course.images.length > 0) ? course.images[0].image_url : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';
                    return `
                        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between p-5 space-y-4 shadow-xs">
                            <div class="space-y-3">
                                <div class="relative aspect-16/9 rounded-xl overflow-hidden bg-slate-100">
                                    <img src="${imgUrl}" class="w-full h-full object-cover">
                                    <span class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded bg-slate-900/80 text-white text-xs font-bold">รหัส ${course.sku}</span>
                                    <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded bg-emerald-600 text-white text-[11px] font-semibold">วางขายแล้ว</span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base line-clamp-2">${course.name}</h3>
                                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200 text-[11px] text-slate-500">
                                    🔒 คอร์สที่วางขายแล้วต้องแจ้งแอดมินเพื่อแก้ไขเท่านั้น
                                </div>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center gap-2">
                                <button onclick="openVideoPlayerModal(${course.id})" class="flex-1 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs cursor-pointer">▶ ดูคลิปที่อัปโหลด</button>
                                <a href="/courses/${course.id}" class="flex-1 py-2 rounded-lg border border-academic-blue text-academic-blue hover:bg-blue-50 font-semibold text-xs text-center cursor-pointer">ดูหน้าคอร์ส</a>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        }
    }

    // ==========================================
    // ระบบเล่นวิดีโอ (ดึงไฟล์มาแบบ 100% บังคับให้โหลดเสร็จก่อนค่อยเล่น)
    // ==========================================
    function openVideoPlayerModal(courseId) {
        const course = allCourses.find(c => Number(c.id) === Number(courseId)) || purchasedCourses.find(c => Number(c.id) === Number(courseId));
        if (!course) return;
        
        document.getElementById('playerCourseTitle').textContent = course.name;
        const meta = parseCourseMeta(course);
        document.getElementById('playerPdfDownloadAnchor').href = meta.pdfUrl;

        const playlist = document.getElementById('playlistContainer');
        
        // ถ้าระบบหลังบ้านส่งข้อมูล lessons มาให้
        if (course.lessons && course.lessons.length > 0) {
            playlist.innerHTML = course.lessons.map((lesson, idx) => `
                <button onclick="playLesson(${lesson.id}, this)" class="playlist-item w-full text-left p-3 rounded-lg text-xs font-medium hover:bg-blue-50 transition border border-transparent ${idx === 0 ? 'bg-blue-50 border-blue-200 text-academic-blue' : 'text-slate-600'}">
                    <div class="font-bold">บทที่ ${idx + 1}</div>
                    <div class="truncate">${lesson.title}</div>
                </button>
            `).join('');
            
            // เล่นบทแรก
            playVideoSource(`/api/stream-video/${course.lessons[0].id}`);
        } else {
            // กรณีเป็นคอร์สเก่า ที่ไม่มีการแบ่งบทเรียน
            playlist.innerHTML = `<div class="p-3 text-xs text-slate-500">บทเรียนแบบคลิปเดียวจบ</div>`;
            playVideoSource(meta.videoUrl);
        }
        
        document.getElementById('videoPlayerModal').classList.remove('hidden');
    }

    function playLesson(lessonId, btnElement) {
        document.querySelectorAll('.playlist-item').forEach(el => el.classList.remove('bg-blue-50', 'border-blue-200', 'text-academic-blue'));
        btnElement.classList.add('bg-blue-50', 'border-blue-200', 'text-academic-blue');
        playVideoSource(`/api/stream-video/${lessonId}`);
    }

    // ฟังก์ชันสูบไฟล์วิดีโอมาเป็นก้อน เพื่อให้ผู้เรียนลากเส้นเวลา (Seek) ไปมาได้ทันที 100%
    async function playVideoSource(url) {
        const videoEl = document.getElementById('realCourseVideoElement');
        const titleEl = document.getElementById('playerCourseTitle');
        const originalTitle = titleEl.textContent;

        videoEl.pause();
        videoEl.src = "";
        titleEl.textContent = "⏳ กำลังดาวน์โหลดวิดีโอทั้งบทเรียน... กรุณารอสักครู่ (ห้ามปิดหน้าต่าง)";

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Network error');
            const blob = await response.blob();
            
            videoEl.src = URL.createObjectURL(blob);
            titleEl.textContent = originalTitle;
            videoEl.load();
            videoEl.play();
        } catch (e) {
            titleEl.textContent = "❌ ดาวน์โหลดวิดีโอล้มเหลว หรือไฟล์วิดีโอหมดอายุ";
        }
    }

    function closeVideoPlayerModal() {
        const videoEl = document.getElementById('realCourseVideoElement');
        videoEl.pause();
        videoEl.src = ""; // ล้างไฟล์เพื่อคืน RAM ให้เครื่อง
        document.getElementById('videoPlayerModal').classList.add('hidden');
        document.getElementById('playerCourseTitle').textContent = "";
    }

    async function handleCreateTutorCoupon(e) {
        e.preventDefault();
        const code = document.getElementById('tutorCouponCode').value;
        const type = document.getElementById('tutorCouponType').value;
        const val = document.getElementById('tutorCouponValue').value;

        try {
            const res = await fetch('/api/tutor/coupons', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${authToken}` },
                body: JSON.stringify({ code, discount_type: type, discount_value: val })
            });
            const data = await res.json();
            if(res.ok) {
                showToast('🎟️ สร้างโค้ดส่วนลดสำเร็จ โค้ดนี้จะใช้ลดได้เฉพาะวิชาของคุณเท่านั้น!');
                document.getElementById('tutorCouponCode').value = '';
                document.getElementById('tutorCouponValue').value = '';
            } else {
                showToast(data.message || 'ไม่สามารถสร้างโค้ดได้', true);
            }
        } catch (err) {}
    }

    window.onPageLanguageChanged = () => renderPanels();
    document.addEventListener('DOMContentLoaded', loadMyCoursesPage);
</script>
@endsection