@extends('layouts.app')

@section('title', 'ค้นหาคอร์สเรียนวิศวกรรมศาสตร์ — EngTutor Academy')

@section('content')
<!-- กรอบค้นหารหัสวิชา 6 หลัก -->
<section class="bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-7 sm:py-9">
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 sm:p-8">
            <div class="max-w-3xl mx-auto text-center space-y-3.5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-academic-blue text-xs font-semibold" data-i18n="hero_badge">
                    แพลตฟอร์มคอร์สเรียนคณะวิศวกรรมศาสตร์
                </span>
                <h1 class="text-xl sm:text-3xl font-bold text-slate-900" data-i18n="hero_title">
                    ค้นหาคอร์สติวและสรุปเนื้อหาจากรหัสวิชา 6 หลัก
                </h1>
                <p class="text-slate-600 text-xs sm:text-sm leading-relaxed" data-i18n="hero_subtitle">
                    เรียนกับติวเตอร์รุ่นพี่ในคณะวิศวกรรมศาสตร์ที่ผ่านการยืนยันตัวตนและผลการเรียน พร้อมคลิปวิดีโอและเอกสาร PDF
                </p>

                <div class="pt-2 flex flex-col sm:flex-row gap-2.5 max-w-2xl mx-auto">
                    <div class="relative flex-1">
                        <input
                            id="courseCodeSearchInput"
                            type="text"
                            oninput="handleSearchInput(this.value)"
                            placeholder="พิมพ์รหัสวิชา 6 หลัก เช่น 259104, 251201, 252211, 261342..."
                            class="w-full pl-4 pr-12 py-3 rounded-xl bg-white border border-slate-300 focus:border-academic-blue focus:outline-none text-sm text-slate-800"
                        >
                        <button id="clearSearchBtn" onclick="setQuickSearch('')" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 hover:text-slate-600 font-bold cursor-pointer">✕</button>
                    </div>
                    <button onclick="renderCourses()" class="px-6 py-3 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-sm transition cursor-pointer shrink-0" data-i18n="btn_search">
                        ค้นหาวิชา
                    </button>
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

<!-- ส่วนเลือกสาขาวิชา และการ์ดคอร์สเรียน -->
<section class="max-w-6xl w-full mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-6">
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
@endsection

@section('scripts')
<script>
    let allCourses = [];
    let allCategories = [];
    let activeCategory = 'ALL';
    let searchQuery = '';

    function extractPrefixFromCategoryName(catName) {
        if (!catName) return '';
        const match = catName.match(/(\d{3})xxx/);
        return match ? match[1] : '';
    }

    async function fetchCourses() {
        const res = await fetch('/api/products', { 
            headers: { 'Accept': 'application/json' },
        });
        const json = await res.json();
        allCourses = json.data || [];
        allCategories = json.categories || [];
        renderCategoryButtons();
        renderCourses();
    }

    function renderCategoryButtons() {
        const bar = document.getElementById('categoryFilterBar');
        const list = [{ name: 'ALL', label: `${t('all_depts')} (${allCourses.length})` }];

        allCategories.forEach(cat => {
            const prefix = extractPrefixFromCategoryName(cat.name);
            const count = allCourses.filter(c => c.category_id === cat.id || (prefix && c.sku && c.sku.startsWith(prefix))).length;
            list.push({ name: cat.name, label: `${cat.name} (${count})` });
        });

        bar.innerHTML = list.map(item => `
            <button onclick="selectCategory('${item.name.replace(/'/g, "\\'")}')" class="px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold transition cursor-pointer ${
                activeCategory === item.name ? 'bg-academic-blue text-white shadow-xs' : 'bg-slate-50 hover:bg-blue-50 hover:text-academic-blue text-slate-700 border border-slate-200'
            }">${item.label}</button>
        `).join('');
    }

    function selectCategory(cat) {
        activeCategory = cat;
        renderCategoryButtons();
        renderCourses();
    }

    function handleSearchInput(val) {
        searchQuery = val.trim();
        document.getElementById('clearSearchBtn').classList.toggle('hidden', searchQuery.length === 0);
        renderCourses();
    }

    function setQuickSearch(code) {
        document.getElementById('courseCodeSearchInput').value = code;
        activeCategory = 'ALL';
        renderCategoryButtons();
        handleSearchInput(code);
    }

    function renderCourses() {
        const grid = document.getElementById('coursesGrid');
        const q = searchQuery.toLowerCase();
        const selectedPrefix = extractPrefixFromCategoryName(activeCategory);

        const filtered = allCourses.filter(c => {
            const matchCat = activeCategory === 'ALL' || (c.category && c.category.name === activeCategory) || (selectedPrefix && c.sku && c.sku.startsWith(selectedPrefix));
            const matchSearch = !q || (c.sku && c.sku.toLowerCase().includes(q)) || c.name.toLowerCase().includes(q) || (c.description && c.description.toLowerCase().includes(q));
            return matchCat && matchSearch;
        });

        document.getElementById('courseCountText').textContent = currentLang === 'th' ? `แสดง ${filtered.length} คอร์สเรียน` : `Showing ${filtered.length} courses`;

        if (filtered.length === 0) {
            const codeText = searchQuery || activeCategory;
            const targetHref = currentUser && currentUser.role === 'tutor' ? `/create-course?sku=${encodeURIComponent(searchQuery)}` : '/apply-tutor';
            grid.innerHTML = `
                <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 max-w-xl mx-auto space-y-3">
                    <div class="w-11 h-11 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-lg mx-auto font-bold">!</div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-900">ยังไม่มีคอร์สเรียนในรหัสวิชา "${codeText}"</h3>
                    <p class="text-xs sm:text-sm text-slate-500">หากคุณเรียนผ่านวิชานี้แล้ว สามารถสมัครเป็นติวเตอร์เพื่ออัปโหลดคลิปเปิดสอนวิชานี้ได้</p>
                    <div class="flex justify-center gap-3 pt-2">
                        <button onclick="setQuickSearch('')" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-xs font-semibold cursor-pointer">ดูคอร์สทั้งหมด</button>
                        <a href="${targetHref}" class="px-4 py-2 rounded-lg bg-academic-blue text-white text-xs font-semibold">${currentUser && currentUser.role === 'tutor' ? '+ ลงขายคอร์สวิชานี้' : 'สมัครเป็นติวเตอร์'}</a>
                    </div>
                </div>
            `;
            return;
        }

        grid.innerHTML = filtered.map(course => {
            const imgUrl = (course.images && course.images.length > 0) ? course.images[0].image_url : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';
            const meta = parseCourseMeta(course);
            const purchased = purchasedCourseIds.has(Number(course.id));

            return `
                <article class="bg-white rounded-2xl border border-slate-200 hover:border-academic-blue/60 shadow-2xs hover:shadow-md transition flex flex-col overflow-hidden">
                    <a href="/courses/${course.id}" class="relative aspect-16/10 bg-slate-900 overflow-hidden group block">
                        <img src="${imgUrl}" alt="${course.name}" class="w-full h-full object-cover opacity-90 group-hover:scale-105 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                        <div class="absolute top-3 left-3 flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-md bg-academic-blue text-white font-bold text-xs shadow-xs">${course.sku}</span>
                            ${purchased
                                ? `<span class="px-2.5 py-1 rounded-md bg-emerald-600 text-white font-bold text-[11px]">✓ ซื้อแล้ว</span>`
                                : `<span class="px-2.5 py-1 rounded-md bg-white/90 text-slate-800 font-semibold text-[11px]">${meta.examType}</span>`
                            }
                        </div>
                        <div class="absolute bottom-2.5 left-3 right-3 flex items-center justify-between text-white text-xs">
                            <span>⏱ ${meta.hoursFormatted}</span>
                            <span>🎬 Video + PDF</span>
                        </div>
                    </a>

                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="text-xs text-academic-blue font-semibold">${course.category ? course.category.name : ''}</div>
                            <a href="/courses/${course.id}" class="text-base font-bold text-slate-900 hover:text-academic-blue transition line-clamp-2 leading-snug block">
                                ${course.name}
                            </a>
                            <div class="pt-1 text-xs text-slate-500">
                                <span class="font-semibold text-slate-800">${meta.tutorName}</span> • <span>${meta.tutorDepartment}</span>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                            <span class="text-xl font-bold text-academic-blue">${Number(course.price).toLocaleString()} ${t('unit_baht')}</span>
                            <div class="flex items-center gap-2">
                                <a href="/courses/${course.id}" class="px-3.5 py-2 rounded-full border border-academic-blue text-academic-blue hover:bg-blue-50 font-semibold text-xs transition">
                                    ${t('btn_detail')}
                                </a>
                                ${purchased ? `
                                    <a href="/my-courses" class="px-3.5 py-2 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition">
                                        ${t('btn_purchased_watch')}
                                    </a>
                                ` : `
                                    <button onclick="addToCart(${course.id})" class="px-4 py-2 rounded-full bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs transition cursor-pointer">
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

    window.onPageLanguageChanged = () => {
        renderCategoryButtons();
        renderCourses();
    };
    window.onAuthChanged = async () => {
        await fetchPurchasedIds();
        renderCourses();
    };

    document.addEventListener('DOMContentLoaded', async () => {
        if (currentUser) await fetchPurchasedIds();
        await fetchCourses();
    });
</script>
@endsection