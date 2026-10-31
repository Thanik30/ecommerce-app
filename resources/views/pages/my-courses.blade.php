@extends('layouts.app')

@section('title', 'คอร์สของฉัน & ห้องเรียนวิดีโอ — EngTutor Academy')

@section('content')
<div class="max-w-6xl w-full mx-auto px-4 sm:px-6 py-8 space-y-6">

    <!-- หัวข้อและโปรไฟล์ย่อ -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
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
            <a href="/profile" class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm transition">
                ⚙️ แก้ไขโปรไฟล์ / รูปภาพ
            </a>
            <a id="myCoursesActionBtn" href="/apply-tutor" class="px-4 py-2 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs sm:text-sm transition">
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

    <div id="purchasedCoursesPanel" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
    <div id="uploadedCoursesPanel" class="hidden grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
</div>

<!-- MODAL: ห้องเรียนวิดีโอจริง (HTML5 Video Player เล่นไฟล์คลิปที่ติวเตอร์อัปโหลด) -->
<div id="videoPlayerModal" class="fixed inset-0 z-50 hidden bg-slate-900/75 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-3xl w-full overflow-hidden shadow-2xl border border-slate-200">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <div>
                <span id="playerCourseSku" class="text-xs font-bold text-sky-400"></span>
                <h3 id="playerCourseTitle" class="font-bold text-base"></h3>
            </div>
            <button onclick="closeVideoPlayerModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center font-bold cursor-pointer">✕</button>
        </div>
        <div class="p-6 space-y-5">
            <!-- ตัวเล่นวิดีโอจริง -->
            <div class="aspect-video rounded-xl bg-black overflow-hidden border border-slate-800">
                <video id="realCourseVideoElement" controls class="w-full h-full object-contain bg-black">
                    <source id="realCourseVideoSource" src="" type="video/mp4">
                    เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                </video>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4 bg-blue-50/70 p-4 rounded-xl border border-blue-200">
                <div class="text-xs sm:text-sm">
                    <p class="font-bold text-slate-900">📄 เอกสารประกอบการเรียน (PDF)</p>
                    <p id="playerTutorName" class="text-slate-600 text-xs"></p>
                </div>
                <a id="playerPdfDownloadAnchor" href="#" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs sm:text-sm transition">
                    📥 เปิดลิงก์ดาวน์โหลด PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let allCourses = [];
    let purchasedCourses = [];

    async function loadMyCoursesPage() {
        // ให้ฟังก์ชันส่วนกลางยืนยันตัวตนด้วย Cookie 
        await fetchMemberProfile();
        
        // เช็คจาก currentUser แทน authToken
        if (!currentUser) {
            window.location.href = '/';
            return;
        }

        // อัปเดตข้อมูลหัวหน้าเว็บ
        const displayName = currentUser.tutor_alias || currentUser.name;
        const avatarUrl = currentUser.avatar_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(displayName) + '&background=0066FF&color=fff&bold=true');
        document.getElementById('myCoursesAvatar').src = avatarUrl;
        document.getElementById('myCoursesUserName').textContent = displayName;
        document.getElementById('myCoursesUserDept').textContent = `${currentUser.department || currentUser.address || 'สาขาวิศวกรรมคอมพิวเตอร์'} | ${currentUser.email}`;

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

        const [prodRes, myRes] = await Promise.all([
            fetch('/api/products', { headers: { 'Accept': 'application/json' }, credentials: 'include' }),
            fetch('/api/my-courses', { headers: { 'Accept': 'application/json' }, credentials: 'include' }) // ลบ Authorization
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
            panelUploaded.classList.add('hidden');
        } else {
            btnUploaded.className = 'whitespace-nowrap pb-3 border-b-2 border-academic-blue text-academic-blue font-bold text-sm sm:text-base transition cursor-pointer';
            btnPurchased.className = 'whitespace-nowrap pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-medium text-sm sm:text-base transition cursor-pointer';
            panelUploaded.classList.remove('hidden');
            panelPurchased.classList.add('hidden');
        }
    }

    function renderPanels() {
        const panelPurchased = document.getElementById('purchasedCoursesPanel');
        const panelUploaded = document.getElementById('uploadedCoursesPanel');
        const uploadedCourses = (currentUser && currentUser.role === 'tutor')
            ? allCourses.filter(c => parseCourseMeta(c).ownerEmail === currentUser.email)
            : [];

        document.getElementById('subTabPurchased').textContent = `${t('tab_purchased')} (${purchasedCourses.length})`;
        document.getElementById('subTabUploaded').textContent = `${t('tab_uploaded')} (${uploadedCourses.length})`;

        if (purchasedCourses.length === 0) {
            panelPurchased.innerHTML = `
                <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 space-y-3">
                    <p class="text-base font-bold text-slate-800">คุณยังไม่มีคอร์สเรียนที่ซื้อไว้</p>
                    <a href="/" class="inline-block px-5 py-2 rounded-lg bg-academic-blue text-white text-xs font-semibold">ไปหน้าค้นหาคอร์สเรียน</a>
                </div>
            `;
        } else {
            panelPurchased.innerHTML = purchasedCourses.map(course => {
                const imgUrl = (course.images && course.images.length > 0) ? course.images[0].image_url : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';
                const meta = parseCourseMeta(course);
                return `
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between shadow-2xs">
                        <div class="flex items-start gap-4">
                            <img src="${imgUrl}" class="w-24 h-24 rounded-xl object-cover shrink-0 border border-slate-200">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded bg-blue-50 text-academic-blue font-bold text-xs">รหัส ${course.sku}</span>
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-semibold text-[11px]">✓ ซื้อแล้ว</span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-sm sm:text-base line-clamp-2">${course.name}</h3>
                                <p class="text-xs text-slate-500">ผู้สอน: ${meta.tutorName} (${meta.tutorDepartment})</p>
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

        if (!currentUser || currentUser.role !== 'tutor') {
            panelUploaded.innerHTML = `
                <div class="col-span-full bg-amber-50/70 rounded-2xl p-10 text-center border border-amber-200 space-y-3">
                    <p class="text-base font-bold text-slate-900">บัญชีของคุณยังไม่ได้ยืนยันตัวตนเป็นติวเตอร์</p>
                    <a href="/apply-tutor" class="inline-block px-6 py-2.5 rounded-xl bg-academic-blue text-white text-xs sm:text-sm font-semibold">🎓 สมัครและยืนยันตัวตนเป็นติวเตอร์</a>
                </div>
            `;
        } else if (uploadedCourses.length === 0) {
            panelUploaded.innerHTML = `
                <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 space-y-3">
                    <p class="text-base font-bold text-slate-800">คุณยังไม่มีคอร์สเรียนที่เปิดขาย</p>
                    <a href="/create-course" class="inline-block px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold">+ ลงขายคอร์สเรียนวิชาแรกของคุณ</a>
                </div>
            `;
        } else {
            panelUploaded.innerHTML = uploadedCourses.map(course => {
                const imgUrl = (course.images && course.images.length > 0) ? course.images[0].image_url : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';
                const meta = parseCourseMeta(course);
                return `
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden flex flex-col justify-between p-5 space-y-4">
                        <div class="space-y-3">
                            <div class="relative aspect-16/9 rounded-xl overflow-hidden bg-slate-100">
                                <img src="${imgUrl}" class="w-full h-full object-cover">
                                <span class="absolute top-2.5 left-2.5 px-2.5 py-1 rounded bg-slate-900/80 text-white text-xs font-bold">รหัส ${course.sku}</span>
                                <span class="absolute top-2.5 right-2.5 px-2.5 py-1 rounded bg-emerald-600 text-white text-[11px] font-semibold">วางขายแล้ว</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm sm:text-base line-clamp-2">${course.name}</h3>
                            <p class="text-xs text-slate-500">⏱ ${meta.hoursFormatted} • ราคา ${Number(course.price).toLocaleString()} บาท</p>
                            <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200 text-[11px] text-slate-500">
                                🔒 คอร์สที่วางขายแล้วต้องแจ้งแอดมินเพื่อแก้ไขเท่านั้น
                            </div>
                        </div>
                        <div class="pt-2 border-t border-slate-100 flex items-center gap-2">
                            <button onclick="openVideoPlayerModal(${course.id})" class="flex-1 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs cursor-pointer">▶ ดูคลิปที่อัปโหลด</button>
                            <button onclick="openContactAdminModal('แจ้งแก้ไขคอร์ส รหัสวิชา ${course.sku}')" class="flex-1 py-2 rounded-lg border border-academic-blue text-academic-blue hover:bg-blue-50 font-semibold text-xs cursor-pointer">🛠️ แจ้งแอดมิน</button>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    function openVideoPlayerModal(courseId) {
        const course = allCourses.find(c => Number(c.id) === Number(courseId)) || purchasedCourses.find(c => Number(c.id) === Number(courseId));
        if (!course) return;
        const meta = parseCourseMeta(course);

        document.getElementById('playerCourseSku').textContent = `รหัสวิชา ${course.sku}`;
        document.getElementById('playerCourseTitle').textContent = course.name;
        document.getElementById('playerTutorName').textContent = `ผู้สอน: ${meta.tutorName} (${meta.tutorDepartment})`;
        document.getElementById('playerPdfDownloadAnchor').href = meta.pdfUrl;

        const videoEl = document.getElementById('realCourseVideoElement');
        const sourceEl = document.getElementById('realCourseVideoSource');
        sourceEl.src = meta.videoUrl;
        videoEl.load();

        document.getElementById('videoPlayerModal').classList.remove('hidden');
    }

    function closeVideoPlayerModal() {
        const videoEl = document.getElementById('realCourseVideoElement');
        videoEl.pause();
        document.getElementById('videoPlayerModal').classList.add('hidden');
    }

    window.onPageLanguageChanged = () => renderPanels();
    document.addEventListener('DOMContentLoaded', loadMyCoursesPage);
</script>
@endsection