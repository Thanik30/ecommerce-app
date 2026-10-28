@extends('layouts.app')

@section('title', 'รายละเอียดคอร์สเรียน — EngTutor Academy')

@section('content')
<div class="max-w-5xl w-full mx-auto px-4 sm:px-6 py-8">
    <div class="mb-6">
        <a href="/" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs sm:text-sm font-medium transition" data-i18n="btn_back_catalog">
            ← ย้อนกลับไปหน้ารวมคอร์สเรียน
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-10 shadow-xs">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

            <!-- คอลัมน์ซ้าย: รูปปก + สถิติ 2 ช่อง -->
            <div class="lg:col-span-5 flex flex-col items-center space-y-5">
                <div class="w-60 sm:w-72 bg-white p-3 rounded-2xl border border-slate-200 book-cover-shadow">
                    <div class="relative aspect-[3/4] rounded-xl overflow-hidden bg-slate-900">
                        <img id="detailImg" src="" alt="Course Cover" class="w-full h-full object-cover opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/30 to-transparent flex flex-col justify-between p-5 text-white">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded bg-academic-blue text-xs font-bold">EngTutor</span>
                                <span id="detailCoverSku" class="px-2.5 py-1 rounded bg-white/20 backdrop-blur-xs text-xs font-bold"></span>
                            </div>
                            <div class="space-y-1">
                                <span id="detailCoverExamType" class="text-[11px] uppercase tracking-wider text-sky-300 font-semibold block"></span>
                                <h4 id="detailCoverTitle" class="text-sm sm:text-base font-bold leading-snug line-clamp-2"></h4>
                                <p id="detailCoverTutor" class="text-xs text-slate-300 pt-1 border-t border-white/20"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full grid grid-cols-2 gap-3 pt-1 text-center">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center">
                        <span id="detailStatHours" class="text-sm font-bold text-slate-800"></span>
                        <span class="text-xs text-slate-500" data-i18n="label_course_hours">ชั่วโมงคอร์ส</span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center">
                        <span id="detailStatDuration" class="text-sm font-bold text-slate-800"></span>
                        <span class="text-xs text-slate-500" data-i18n="label_course_duration">อายุคอร์สเรียน</span>
                    </div>
                </div>
            </div>

            <!-- คอลัมน์ขวา -->
            <div class="lg:col-span-7 space-y-5">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span id="detailBadgeSku" class="px-3 py-1 rounded-md bg-blue-50 text-academic-blue font-bold text-xs border border-blue-200"></span>
                        <span id="detailBadgeCat" class="px-3 py-1 rounded-md bg-slate-100 text-slate-700 font-medium text-xs"></span>
                        <span id="detailPurchasedStatusBadge" class="hidden px-3 py-1 rounded-md bg-emerald-100 text-emerald-800 font-bold text-xs">✓ คุณซื้อคอร์สเรียนนี้แล้ว</span>
                    </div>
                    <h1 id="detailTitle" class="text-xl sm:text-2xl font-bold text-slate-900 leading-snug pt-1"></h1>
                </div>

                <div>
                    <div id="detailPrice" class="text-3xl font-bold text-academic-blue"></div>
                </div>

                <div class="space-y-4 text-sm sm:text-base text-slate-700 text-body-thai">
                    <p><strong class="underline text-slate-900 font-semibold" data-i18n="label_suitable_for">เหมาะสำหรับ :</strong> <span id="detailSuitableFor"></span></p>
                    <p><strong class="underline text-slate-900 font-semibold" data-i18n="label_course_desc">รายละเอียดคอร์ส :</strong> <span id="detailDescription"></span></p>
                </div>

                <hr class="border-slate-200">

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button id="detailBuyBtn" class="px-8 py-3 rounded-full bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-sm sm:text-base shadow-md transition cursor-pointer">
                        สั่งซื้อคอร์สเรียน
                    </button>
                    <button onclick="openContactTutorModal()" class="px-6 py-3 rounded-full border-2 border-academic-blue text-academic-blue hover:bg-blue-50 font-semibold text-sm sm:text-base transition cursor-pointer" data-i18n="btn_contact_tutor">
                        💬 ติดต่อผู้สอน
                    </button>
                </div>
            </div>
        </div>

        <hr class="my-10 border-slate-200">

        <!-- ส่วนล่าง: หัวข้อบทเรียน & ข้อมูลผู้สอน (แสดงสาขาของผู้สอน) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-6 space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-1 h-6 bg-academic-blue rounded-full"></div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900" data-i18n="section_syllabus">หัวข้อบทเรียนในคอร์ส (Syllabus)</h3>
                </div>
                <div id="detailSyllabusList" class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-3 text-sm text-slate-700"></div>
            </div>

            <div class="lg:col-span-6 space-y-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-1 h-6 bg-academic-blue rounded-full"></div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900" data-i18n="section_instructor_reviews">ข้อมูลผู้สอนและรีวิวจากผู้เรียน</h3>
                </div>

                <div class="bg-blue-50/60 border border-blue-200 rounded-2xl p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-xl bg-academic-blue text-white flex items-center justify-center font-bold text-sm shrink-0">TUTOR</div>
                        <div>
                            <div id="detailBottomTutor" class="text-base font-bold text-slate-900"></div>
                            <div id="detailBottomTutorDept" class="text-xs font-semibold text-academic-blue mt-0.5"></div>
                        </div>
                    </div>
                    <button onclick="openContactTutorModal()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-academic-blue border border-blue-200 text-xs font-semibold transition cursor-pointer shrink-0" data-i18n="btn_contact_tutor">
                        💬 ติดต่อผู้สอน
                    </button>
                </div>

                <div id="detailReviewsList" class="space-y-2.5 max-h-48 overflow-y-auto pr-1"></div>

                <form onsubmit="submitReview(event)" class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-slate-700" data-i18n="write_review_label">เขียนรีวิวให้คอร์สเรียนนี้:</span>
                        <select id="reviewRating" class="px-3 py-1 rounded-lg bg-white border border-slate-300 text-xs font-semibold text-slate-800">
                            <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                            <option value="4">⭐⭐⭐⭐ (4)</option>
                            <option value="3">⭐⭐⭐ (3)</option>
                            <option value="2">⭐⭐ (2)</option>
                            <option value="1">⭐ (1)</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <input id="reviewComment" type="text" required placeholder="แสดงความคิดเห็นหลังเรียน..." class="flex-1 px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-xs sm:text-sm focus:outline-none focus:border-academic-blue">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs sm:text-sm transition cursor-pointer shrink-0">ส่งรีวิว</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: ติดต่อผู้สอน -->
<div id="contactTutorModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200">
        <div class="px-6 py-4 bg-academic-blue text-white flex items-center justify-between">
            <h3 class="font-bold text-base">💬 ติดต่อผู้สอนคอร์สเรียน</h3>
            <button onclick="closeContactTutorModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
        </div>
        <form onsubmit="handleSendTutorMessage(event)" class="p-6 space-y-4 text-sm">
            <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 space-y-1 text-xs">
                <div class="font-bold text-slate-900 text-sm" id="contactTutorNameDisplay"></div>
                <div class="text-academic-blue font-semibold" id="contactTutorDeptDisplay"></div>
                <div class="text-slate-500" id="contactTutorCourseDisplay"></div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ข้อความถึงผู้สอน *</label>
                <textarea id="tutorMessageInput" rows="3" required placeholder="สอบถามเนื้อหาหรือนัดหมายถามโจทย์..." class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="closeContactTutorModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer">ปิด</button>
                <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">ส่งข้อความ</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const targetCourseId = Number("{{ $courseId }}");
    let currentCourse = null;

    async function loadCourseDetail() {
        const res = await fetch('/api/products', { headers: { 'Accept': 'application/json' } });
        const json = await res.json();
        const courses = json.data || [];
        currentCourse = courses.find(c => Number(c.id) === targetCourseId);
        if (!currentCourse) {
            window.location.href = '/';
            return;
        }
        renderCourseDetail();
    }

    function renderCourseDetail() {
        if (!currentCourse) return;
        const meta = parseCourseMeta(currentCourse);
        const purchased = purchasedCourseIds.has(Number(currentCourse.id));
        const imgUrl = (currentCourse.images && currentCourse.images.length > 0) ? currentCourse.images[0].image_url : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';

        document.getElementById('detailImg').src = imgUrl;
        document.getElementById('detailCoverSku').textContent = currentCourse.sku;
        document.getElementById('detailCoverExamType').textContent = meta.examType;
        document.getElementById('detailCoverTitle').textContent = currentCourse.name;
        document.getElementById('detailCoverTutor').textContent = `${meta.tutorName} (${meta.tutorDepartment})`;

        document.getElementById('detailStatHours').textContent = meta.hoursFormatted;
        document.getElementById('detailStatDuration').textContent = meta.duration;

        document.getElementById('detailBadgeSku').textContent = `รหัสวิชา ${currentCourse.sku}`;
        document.getElementById('detailBadgeCat').textContent = currentCourse.category ? currentCourse.category.name : 'วิศวกรรมศาสตร์';
        document.getElementById('detailTitle').textContent = currentCourse.name;
        document.getElementById('detailPrice').textContent = `${Number(currentCourse.price).toLocaleString()} ${t('unit_baht')}`;
        document.getElementById('detailSuitableFor').textContent = meta.suitableFor;
        document.getElementById('detailDescription').textContent = currentCourse.description || '';

        document.getElementById('detailBottomTutor').textContent = meta.tutorName;
        document.getElementById('detailBottomTutorDept').textContent = meta.tutorDepartment;

        const syllabusLines = meta.syllabus.split('\n').filter(l => l.trim().length > 0);
        document.getElementById('detailSyllabusList').innerHTML = syllabusLines.map((line, idx) => `
            <div class="flex items-start gap-3 ${idx < syllabusLines.length - 1 ? 'pb-3 border-b border-slate-200/80' : ''}">
                <span class="text-academic-blue font-bold">0${idx + 1}.</span>
                <div class="font-medium text-slate-800">${line}</div>
            </div>
        `).join('');

        const buyBtn = document.getElementById('detailBuyBtn');
        const badge = document.getElementById('detailPurchasedStatusBadge');
        if (purchased) {
            badge.classList.remove('hidden');
            buyBtn.textContent = '✓ ซื้อคอร์สนี้แล้ว (คลิกเพื่อเข้าเรียนวิดีโอ)';
            buyBtn.className = 'px-8 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm sm:text-base shadow-md transition cursor-pointer';
            buyBtn.onclick = () => { window.location.href = '/my-courses'; };
        } else {
            badge.classList.add('hidden');
            buyBtn.textContent = t('btn_buy');
            buyBtn.className = 'px-8 py-3 rounded-full bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-sm sm:text-base shadow-md transition cursor-pointer';
            buyBtn.onclick = () => addToCart(currentCourse.id);
        }

        const reviews = currentCourse.reviews || [];
        const list = document.getElementById('detailReviewsList');
        if (reviews.length === 0) {
            list.innerHTML = `<div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-400">ยังไม่มีรีวิวสำหรับคอร์สเรียนนี้</div>`;
        } else {
            list.innerHTML = reviews.map(r => `
                <div class="p-3.5 rounded-xl bg-white border border-slate-200 text-xs space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-800">${r.user ? (r.user.tutor_alias || r.user.name) : 'นักศึกษา'}</span>
                        <span class="text-amber-500 font-bold">${'★'.repeat(r.rating)}</span>
                    </div>
                    <p class="text-slate-600 leading-relaxed">${r.comment || ''}</p>
                </div>
            `).join('');
        }
    }

    function openContactTutorModal() {
        if (!currentCourse) return;
        const meta = parseCourseMeta(currentCourse);
        document.getElementById('contactTutorNameDisplay').textContent = `ผู้สอน: ${meta.tutorName}`;
        document.getElementById('contactTutorDeptDisplay').textContent = meta.tutorDepartment;
        document.getElementById('contactTutorCourseDisplay').textContent = `รหัสวิชา ${currentCourse.sku} — ${currentCourse.name}`;
        document.getElementById('contactTutorModal').classList.remove('hidden');
    }
    function closeContactTutorModal() {
        document.getElementById('contactTutorModal').classList.add('hidden');
    }
    function handleSendTutorMessage(e) {
        e.preventDefault();
        closeContactTutorModal();
        showToast('📨 ส่งข้อความถึงผู้สอนเรียบร้อยแล้ว');
    }

    async function submitReview(e) {
        e.preventDefault();
        if (!authToken) {
            openAuthModal('login');
            return;
        }
        const rating = parseInt(document.getElementById('reviewRating').value);
        const comment = document.getElementById('reviewComment').value.trim();
        const res = await fetch('/api/reviews', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` },
            body: JSON.stringify({ product_id: currentCourse.id, rating, comment })
        });
        if (res.ok) {
            document.getElementById('reviewComment').value = '';
            showToast('บันทึกรีวิวเรียบร้อยแล้ว');
            await loadCourseDetail();
        }
    }

    window.onPageLanguageChanged = () => renderCourseDetail();
    window.onAuthChanged = async () => {
        await fetchPurchasedIds();
        renderCourseDetail();
    };

    document.addEventListener('DOMContentLoaded', async () => {
        await fetchPurchasedIds();
        await loadCourseDetail();
    });
</script>
@endsection