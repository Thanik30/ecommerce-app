@extends('layouts.app')

@section('title', 'EngTutor Academy — ระบบคอร์สเรียนและติวเตอร์คณะวิศวกรรมศาสตร์')

@section('content')
<!-- VIEW 1: CATALOG -->
<div id="viewCatalog" class="flex-1">
    <section class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-7 sm:py-9">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 sm:p-8">
                <div class="max-w-3xl mx-auto text-center space-y-3.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-academic-blue text-xs font-semibold" data-i18n="hero_badge">แพลตฟอร์มคอร์สเรียนคณะวิศวกรรมศาสตร์</span>
                    <h1 class="text-xl sm:text-3xl font-bold text-slate-900" data-i18n="hero_title">ค้นหาคอร์สติวและสรุปเนื้อหาจากรหัสวิชา 6 หลัก</h1>
                    <p class="text-slate-600 text-xs sm:text-sm leading-relaxed" data-i18n="hero_subtitle">เรียนกับติวเตอร์รุ่นพี่ในคณะวิศวกรรมศาสตร์ที่ผ่านการยืนยันตัวตนและผลการเรียน พร้อมเอกสารประกอบการเรียน PDF</p>

                    <div class="pt-2 flex flex-col sm:flex-row gap-2.5 max-w-2xl mx-auto">
                        <div class="relative flex-1">
                            <input id="courseCodeSearchInput" type="text" oninput="handleSearchInput(this.value)" placeholder="พิมพ์รหัสวิชา 6 หลัก เช่น 259104, 251201, 252211, 261342..." class="w-full pl-4 pr-16 py-3 rounded-xl bg-white border border-slate-300 focus:border-academic-blue focus:outline-none text-sm text-slate-800">
                            <button id="clearSearchBtn" onclick="setQuickSearch('')" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 hover:text-slate-600 font-medium cursor-pointer">✕</button>
                        </div>
                        <button onclick="renderCourses()" class="px-6 py-3 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-sm transition cursor-pointer shrink-0" data-i18n="btn_search">ค้นหาวิชา</button>
                    </div>
                    <div class="pt-1 flex flex-wrap items-center justify-center gap-2 text-xs">
                        <span class="text-slate-500" data-i18n="popular_codes">รหัสวิชายอดนิยม:</span>
                        <button onclick="setQuickSearch('259104')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-academic-blue hover:text-academic-blue text-slate-700 font-medium transition cursor-pointer">259104</button>
                        <button onclick="setQuickSearch('251201')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-academic-blue hover:text-academic-blue text-slate-700 font-medium transition cursor-pointer">251201</button>
                        <button onclick="setQuickSearch('252211')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-academic-blue hover:text-academic-blue text-slate-700 font-medium transition cursor-pointer">252211</button>
                        <button onclick="setQuickSearch('254202')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-academic-blue hover:text-academic-blue text-slate-700 font-medium transition cursor-pointer">254202</button>
                        <button onclick="setQuickSearch('261102')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-academic-blue hover:text-academic-blue text-slate-700 font-medium transition cursor-pointer">261102</button>
                        <button onclick="setQuickSearch('261342')" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-academic-blue hover:text-academic-blue text-slate-700 font-medium transition cursor-pointer">261342</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-6">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-1.5 h-6 bg-academic-blue rounded-full"></div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900" data-i18n="dept_filter_title">แยกตามสาขาวิชา (รหัสขึ้นต้น 3 หลัก)</h2>
                </div>
                <span id="courseCountText" class="text-xs text-slate-500 font-medium"></span>
            </div>
            <div id="categoryFilterBar" class="flex flex-wrap items-center gap-2"></div>
        </div>
        <div id="coursesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </section>
</div>

<!-- VIEW 2: COURSE DETAIL -->
<div id="viewCourseDetail" class="hidden flex-1 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-6">
            <button onclick="switchMainView('catalog')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs sm:text-sm font-medium transition cursor-pointer" data-i18n="btn_back_catalog">
                ← ย้อนกลับไปหน้ารวมคอร์สเรียน
            </button>
        </div>
        <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-10 shadow-xs">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
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
                            <svg class="w-7 h-7 text-academic-blue mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="13" r="8"/><path stroke-linecap="round" d="M12 9v4l2.5 2.5M9 2h6"/></svg>
                            <span id="detailStatHours" class="text-sm font-bold text-slate-800"></span>
                            <span class="text-xs text-slate-500" data-i18n="label_course_hours">ชั่วโมงคอร์ส</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center">
                            <svg class="w-7 h-7 text-academic-blue mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                            <span id="detailStatDuration" class="text-sm font-bold text-slate-800"></span>
                            <span class="text-xs text-slate-500" data-i18n="label_course_duration">อายุคอร์สเรียน</span>
                        </div>
                    </div>
                </div>

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
                        <p><strong class="underline text-slate-900 font-semibold" data-i18n="label_suitable_for">เหมาะสำหรับ :</strong><span id="detailSuitableFor"></span></p>
                        <p><strong class="underline text-slate-900 font-semibold" data-i18n="label_course_desc">รายละเอียดคอร์ส :</strong><span id="detailDescription"></span></p>
                    </div>
                    <hr class="border-slate-200">
                    <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-700">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-academic-blue text-white text-xs font-bold">✓</span>
                        <span class="font-medium text-slate-700" data-i18n="pdf_included_note">รวมลิงก์ดาวน์โหลดไฟล์เอกสารประกอบการเรียน (PDF) พร้อมเข้าเรียนทันทีหลังชำระเงิน</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <button id="detailBuyBtn" class="px-8 py-3 rounded-full bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-sm sm:text-base shadow-md shadow-blue-500/20 transition cursor-pointer">สั่งซื้อคอร์สเรียน</button>
                        <button onclick="openContactTutorModal()" class="px-6 py-3 rounded-full border-2 border-academic-blue text-academic-blue hover:bg-blue-50 font-semibold text-sm sm:text-base transition cursor-pointer" data-i18n="btn_contact_tutor">💬 ติดต่อผู้สอน</button>
                    </div>
                </div>
            </div>

            <hr class="my-10 border-slate-200">

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
                        <button onclick="openContactTutorModal()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 text-academic-blue border border-blue-200 text-xs font-semibold transition cursor-pointer shrink-0" data-i18n="btn_contact_tutor">💬 ติดต่อผู้สอน</button>
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
</div>
@endsection

@section('scripts')
<script>
    let allCourses = [];
    let allCategories = [];
    let activeCategory = 'ALL';
    let searchQuery = '';
    let currentDetailCourse = null;

    function extractPrefixFromCategoryName(catName) {
        if (!catName) return '';
        const match = catName.match(/(\d{3})xxx/);
        return match ? match[1] : '';
    }

    async function fetchCourses() {
        try {
            const res = await fetch('/api/products', { 
                headers: { 'Accept': 'application/json' }
            });
            const json = await res.json();
            allCourses = json.data || [];
            allCategories = json.categories || [];
            renderCategoryButtons();
            renderCourses();
        } catch (err) {
            const cText = document.getElementById('courseCountText');
            if (cText) cText.textContent = 'ไม่สามารถดึงข้อมูลคอร์สเรียนได้';
        }
    }

    function renderCategoryButtons() {
        const bar = document.getElementById('categoryFilterBar');
        if (!bar) return;
        const categoriesList = [{ name: 'ALL', label: `${t('all_depts')} (${allCourses.length})` }];

        allCategories.forEach(cat => {
            const prefix = extractPrefixFromCategoryName(cat.name);
            const count = allCourses.filter(c => {
                const matchId = c.category_id === cat.id;
                const matchPrefix = prefix && c.sku && c.sku.startsWith(prefix);
                return matchId || matchPrefix;
            }).length;
            categoriesList.push({ name: cat.name, label: `${cat.name} (${count})` });
        });

        bar.innerHTML = categoriesList.map(item => {
            const isActive = activeCategory === item.name;
            return `
                <button
                    onclick="selectCategory('${item.name.replace(/'/g, "\\'")}')"
                    class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition cursor-pointer ${
                        isActive
                            ? 'bg-academic-blue text-white shadow-xs'
                            : 'bg-slate-50 hover:bg-blue-50 hover:text-academic-blue text-slate-700 border border-slate-200'
                    }"
                >
                    ${item.label}
                </button>
            `;
        }).join('');
    }

    function selectCategory(cat) {
        activeCategory = cat;
        renderCategoryButtons();
        renderCourses();
    }

    function handleSearchInput(val) {
        searchQuery = val.trim();
        const clearBtn = document.getElementById('clearSearchBtn');
        if (clearBtn) {
            if (searchQuery.length > 0) clearBtn.classList.remove('hidden');
            else clearBtn.classList.add('hidden');
        }
        renderCourses();
    }

    function setQuickSearch(code) {
        const input = document.getElementById('courseCodeSearchInput');
        if (input) input.value = code;
        activeCategory = 'ALL';
        renderCategoryButtons();
        handleSearchInput(code);
    }

    function renderCourses() {
        const grid = document.getElementById('coursesGrid');
        if (!grid) return;
        const q = searchQuery.toLowerCase();
        const selectedPrefix = extractPrefixFromCategoryName(activeCategory);

        const filtered = allCourses.filter(c => {
            const matchCat = activeCategory === 'ALL' ||
                (c.category && c.category.name === activeCategory) ||
                (selectedPrefix && c.sku && c.sku.startsWith(selectedPrefix));

            const matchSearch = !q ||
                (c.sku && c.sku.toLowerCase().includes(q)) ||
                c.name.toLowerCase().includes(q) ||
                (c.description && c.description.toLowerCase().includes(q));
            return matchCat && matchSearch;
        });

        const countText = document.getElementById('courseCountText');
        if (countText) {
            countText.textContent = currentLang === 'th'
                ? `แสดง ${filtered.length} คอร์สเรียน`
                : `Showing ${filtered.length} courses`;
        }

        if (filtered.length === 0) {
            const codeText = searchQuery || activeCategory;
            const targetHref = currentUser && currentUser.role === 'tutor' ? `/create-course?sku=${encodeURIComponent(searchQuery)}` : '/apply-tutor';
            grid.innerHTML = `
                <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 max-w-xl mx-auto space-y-3">
                    <div class="w-11 h-11 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-lg mx-auto font-bold">!</div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">ยังไม่มีคอร์สเรียนในรหัสวิชา "${codeText}"</h3>
                    <p class="text-xs sm:text-sm text-slate-500">หากคุณเรียนผ่านวิชานี้แล้ว สามารถสมัครเป็นติวเตอร์เพื่อเปิดสอนวิชานี้ได้</p>
                    <div class="flex justify-center gap-3 pt-2">
                        <button onclick="setQuickSearch('')" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-xs font-semibold cursor-pointer">ดูคอร์สทั้งหมด</button>
                        <a href="${targetHref}" class="px-4 py-2 rounded-lg bg-academic-blue text-white text-xs font-semibold cursor-pointer">
                            ${currentUser && currentUser.role === 'tutor' ? '+ ลงขายคอร์สวิชานี้' : 'สมัครเป็นติวเตอร์'}
                        </a>
                    </div>
                </div>
            `;
            return;
        }

        grid.innerHTML = filtered.map(course => {
            const imgUrl = (course.images && course.images.length > 0)
                ? course.images[0].image_url
                : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';
            const meta = parseCourseMeta(course);
            const priceNum = Number(course.price);
            const purchased = purchasedCourseIds.has(Number(course.id));

            return `
                <article class="bg-white rounded-2xl border border-slate-200 hover:border-academic-blue/60 shadow-2xs hover:shadow-md transition flex flex-col overflow-hidden">
                    <a href="#" onclick="openCourseDetailPage(${course.id}, true); return false;" class="relative aspect-16/10 bg-slate-900 overflow-hidden group block cursor-pointer">
                        <img src="${imgUrl}" alt="${course.name}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute top-3 left-3 flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-md bg-academic-blue text-white font-bold text-xs shadow-xs">
                                ${course.sku || '259xxx'}
                            </span>
                            ${purchased ? `
                                <span class="px-2.5 py-1 rounded-md bg-emerald-600 text-white font-bold text-[11px]">✓ ซื้อแล้ว</span>
                            ` : `
                                <span class="px-2.5 py-1 rounded-md bg-white/90 text-slate-800 font-semibold text-[11px]">${meta.examType}</span>
                            `}
                        </div>
                        <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-white text-xs">
                            <span>⏱ ${meta.hoursFormatted}</span>
                            <span>📄 PDF</span>
                        </div>
                    </a>

                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="text-xs text-academic-blue font-semibold">${course.category ? course.category.name : ''}</div>
                            <a href="#" onclick="openCourseDetailPage(${course.id}, true); return false;" class="text-base font-bold text-slate-900 hover:text-academic-blue transition line-clamp-2 leading-snug cursor-pointer block">
                                ${course.name}
                            </a>
                            <div class="pt-1 text-xs text-slate-500">
                                <span class="font-semibold text-slate-800">${meta.tutorName}</span> • <span>${meta.tutorDepartment}</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <span class="text-xl font-bold text-academic-blue">${priceNum.toLocaleString()} ${t('unit_baht')}</span>
                            <div class="flex items-center gap-2">
                                <button
                                    onclick="openCourseDetailPage(${course.id})"
                                    class="px-3.5 py-2 rounded-full border border-academic-blue text-academic-blue hover:bg-blue-50 font-semibold text-xs transition cursor-pointer"
                                >
                                    ${t('btn_detail')}
                                </button>
                                ${purchased ? `
                                    <button
                                        onclick="openVideoPlayerModal(${course.id})"
                                        class="px-3.5 py-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition cursor-pointer"
                                    >
                                        ${t('btn_purchased_watch')}
                                    </button>
                                ` : `
                                    <button
                                        onclick="addToCart(${course.id})"
                                        class="px-4 py-2 rounded-full bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs transition cursor-pointer"
                                    >
                                        ${t('btn_buy')}
                                    </button>
                                `}
                            </div>
                        </div>
                    </div>
                </article>
            `;
        }).join('');
    }

    function switchMainView(viewName) {
        const vCatalog = document.getElementById('viewCatalog');
        const vDetail = document.getElementById('viewCourseDetail');
        if (vCatalog && vDetail) {
            vCatalog.classList.add('hidden');
            vDetail.classList.add('hidden');

            if (viewName === 'catalog') {
                vCatalog.classList.remove('hidden');
                renderCourses();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (viewName === 'detail') {
                vDetail.classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    }

    function openCourseDetailPage(courseId, switchView = true) {
        const course = allCourses.find(c => c.id === courseId);
        if (!course) return;
        currentDetailCourse = course;

        const meta = parseCourseMeta(course);
        const priceNum = Number(course.price);
        const purchased = purchasedCourseIds.has(Number(course.id));
        const imgUrl = (course.images && course.images.length > 0)
            ? course.images[0].image_url
            : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';

        document.getElementById('detailImg').src = imgUrl;
        document.getElementById('detailCoverSku').textContent = course.sku || '259xxx';
        document.getElementById('detailCoverExamType').textContent = meta.examType;
        document.getElementById('detailCoverTitle').textContent = course.name;
        document.getElementById('detailCoverTutor').textContent = `${meta.tutorName} (${meta.tutorDepartment})`;

        document.getElementById('detailStatHours').textContent = meta.hoursFormatted;
        document.getElementById('detailStatDuration').textContent = meta.duration;

        document.getElementById('detailBadgeSku').textContent = `รหัสวิชา ${course.sku}`;
        document.getElementById('detailBadgeCat').textContent = course.category ? course.category.name : 'วิศวกรรมศาสตร์';
        document.getElementById('detailTitle').textContent = course.name;
        document.getElementById('detailPrice').textContent = `${priceNum.toLocaleString()} ${t('unit_baht')}`;

        document.getElementById('detailSuitableFor').textContent = ' ' + meta.suitableFor;
        document.getElementById('detailDescription').textContent = ' ' + (course.description || '');

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
        const purchasedBadge = document.getElementById('detailPurchasedStatusBadge');

        if (purchased) {
            purchasedBadge.classList.remove('hidden');
            buyBtn.textContent = '✓ ซื้อคอร์สนี้แล้ว (คลิกเพื่อเข้าเรียน & โหลด PDF)';
            buyBtn.className = 'px-8 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm sm:text-base shadow-md transition cursor-pointer';
            buyBtn.onclick = () => openVideoPlayerModal(course.id);
        } else {
            purchasedBadge.classList.add('hidden');
            buyBtn.textContent = 'สั่งซื้อคอร์สเรียน';
            buyBtn.className = 'px-8 py-3 rounded-full bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-sm sm:text-base shadow-md shadow-blue-500/20 transition cursor-pointer';
            buyBtn.onclick = () => addToCart(course.id);
        }

        renderDetailReviews(course.reviews || []);
        if (switchView) switchMainView('detail');
    }

    function renderDetailReviews(reviews) {
        const list = document.getElementById('detailReviewsList');
        if (reviews.length === 0) {
            list.innerHTML = `<div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-400">ยังไม่มีรีวิวสำหรับคอร์สเรียนนี้</div>`;
            return;
        }
        list.innerHTML = reviews.map(r => `
            <div class="p-3.5 rounded-xl bg-white border border-slate-200 text-xs space-y-1">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">${r.user ? r.user.name : 'นักศึกษา'}</span>
                    <span class="text-amber-500 font-bold">${'★'.repeat(r.rating)}</span>
                </div>
                <p class="text-slate-600 leading-relaxed">${r.comment || ''}</p>
            </div>
        `).join('');
    }

    async function submitReview(e) {
        e.preventDefault();
        if (!authToken) {
            showToast('กรุณาเข้าสู่ระบบก่อนรีวิวคอร์สเรียน', true);
            openAuthModal('login');
            return;
        }
        if (!currentDetailCourse) return;

        const rating = parseInt(document.getElementById('reviewRating').value);
        const comment = document.getElementById('reviewComment').value.trim();

        try {
            const res = await fetch('/api/reviews', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${authToken}` 
                },
                body: JSON.stringify({
                    product_id: currentDetailCourse.id,
                    rating,
                    comment
                })
            });
            const data = await res.json();
            if (!res.ok) {
                showToast(data.message || 'ส่งรีวิวไม่สำเร็จ', true);
                return;
            }
            document.getElementById('reviewComment').value = '';
            showToast('บันทึกความคิดเห็นเรียบร้อยแล้ว');
            await fetchCourses();
            const updated = allCourses.find(c => c.id === currentDetailCourse.id);
            if (updated) renderDetailReviews(updated.reviews || []);
        } catch (err) {
            showToast('เกิดข้อผิดพลาดในการส่งรีวิว', true);
        }
    }

    function openContactTutorModal() {
        if (!currentDetailCourse) return;
        const meta = parseCourseMeta(currentDetailCourse);
        document.getElementById('contactTutorNameDisplay').textContent = `ผู้สอน: ${meta.tutorName}`;
        document.getElementById('contactTutorDeptDisplay').textContent = meta.tutorDepartment;
        document.getElementById('contactTutorCourseDisplay').textContent = `รหัสวิชา ${currentDetailCourse.sku} — ${currentDetailCourse.name}`;
        document.getElementById('tutorMessageInput').value = '';
        document.getElementById('contactTutorModal').classList.remove('hidden');
    }
    function closeContactTutorModal() {
        document.getElementById('contactTutorModal').classList.add('hidden');
    }
    function handleSendTutorMessage(e) {
        e.preventDefault();
        closeContactTutorModal();
        showToast('📨 ส่งข้อความถึงผู้สอนเรียบร้อยแล้ว ผู้สอนจะติดต่อกลับทางอีเมล/เบอร์โทรศัพท์ของคุณ');
    }

    function openVideoPlayerModal(courseId) {
        // แก้บั๊กที่ค้นหาจาก purchasedCourses ที่ไม่ได้ประกาศ
        const course = allCourses.find(c => c.id === courseId);
        if (!course) return;
        const meta = parseCourseMeta(course);
        document.getElementById('playerCourseSku').textContent = `รหัสวิชา ${course.sku}`;
        document.getElementById('playerCourseTitle').textContent = course.name;
        document.getElementById('playerTutorName').textContent = `ผู้สอน: ${meta.tutorName} (${meta.tutorDepartment})`;
        document.getElementById('playerPdfDownloadAnchor').href = meta.pdfUrl;
        document.getElementById('playerPdfLinkText').textContent = meta.pdfUrl;

        const videoEl = document.getElementById('courseVideoPlayer');
        const sourceEl = document.getElementById('courseVideoSource');
        const placeholder = document.getElementById('courseVideoPlaceholder');

        if (meta.videoUrl && meta.videoUrl !== 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4') {
            sourceEl.src = meta.videoUrl;
            videoEl.load();
            videoEl.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            videoEl.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        document.getElementById('videoPlayerModal').classList.remove('hidden');
    }
    function closeVideoPlayerModal() {
        const videoEl = document.getElementById('courseVideoPlayer');
        if (videoEl) videoEl.pause();
        document.getElementById('videoPlayerModal').classList.add('hidden');
    }

    window.onPageLanguageChanged = () => {
        renderCategoryButtons();
        renderCourses();
    };
    
    window.onAuthChanged = async () => {
        if (currentUser) {
            await fetchPurchasedIds();
        } else {
            purchasedCourseIds.clear();
        }
        renderCourses();
    };

    document.addEventListener('DOMContentLoaded', async () => {
        if (currentUser) {
            await fetchPurchasedIds();
        }
        await fetchCourses();
    });
</script>
@endsection