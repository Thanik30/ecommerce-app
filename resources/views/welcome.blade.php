<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EngTutor Academy — ระบบคอร์สเรียนและติวเตอร์คณะวิศวกรรมศาสตร์</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Sarabun:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        academic: {
                            blue: '#0066FF',
                            blueHover: '#0052CC',
                            lightBlue: '#EFF6FF',
                            green: '#16A34A',
                            greenBg: '#F0FDF4',
                        }
                    },
                    fontFamily: {
                        sans: ['Prompt', 'Sarabun', 'sans-serif'],
                        body: ['Sarabun', 'Prompt', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
        }
        .text-body-thai {
            font-family: 'Prompt', sans-serif;
            line-height: 1.85;
        }
        .book-cover-shadow {
            box-shadow: 0 18px 30px -10px rgba(15, 23, 42, 0.16), 0 6px 12px -6px rgba(15, 23, 42, 0.08);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- แถบแจ้งโค้ดส่วนลดด้านบนสุด -->
    <div class="bg-slate-900 text-white text-xs py-2 px-4">
        <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-academic-blue text-white font-semibold text-[11px]">PROMOTION</span>
                <span id="topPromoText">โค้ดส่วนลดช่วงสอบ: คลิกคัดลอก <button onclick="copyCoupon('ENGMID20')" class="underline font-semibold text-sky-300 hover:text-white cursor-pointer">ENGMID20</button> (ลด 20%) หรือ <button onclick="copyCoupon('PASSA100')" class="underline font-semibold text-sky-300 hover:text-white cursor-pointer">PASSA100</button> (ลด 100฿)</span>
            </div>
            <button onclick="openContactAdminModal('สอบถามทั่วไป / แจ้งปัญหาการใช้งาน')" class="text-sky-300 hover:text-white underline text-xs cursor-pointer" data-i18n="nav_contact_admin">
                ติดต่อแอดมิน
            </button>
        </div>
    </div>

    <!-- NAVBAR -->
    <header class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-2xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-3">
            <button onclick="switchMainView('catalog')" class="flex items-center gap-2.5 text-left shrink-0 cursor-pointer">
                <div class="w-9 h-9 rounded-lg bg-academic-blue flex items-center justify-center text-white font-bold text-lg">
                    E
                </div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-slate-900 block leading-none">EngTutor</span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Engineering Course Platform</span>
                </div>
            </button>

            <nav class="hidden lg:flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                <button id="navTabCatalog" onclick="switchMainView('catalog')" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer bg-white text-academic-blue shadow-xs" data-i18n="nav_search_courses">ค้นหาคอร์สเรียน</button>
                <button id="navTabMyCourses" onclick="switchMainView('my-courses')" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
                    <span data-i18n="nav_my_courses">คอร์สของฉัน</span>
                    <span id="myCoursesCountBadge" class="px-1.5 py-0.2 rounded-full bg-academic-blue/10 text-academic-blue text-xs font-semibold">0</span>
                </button>
                <button id="navBtnApplyTutor" onclick="openTutorApplicationModal()" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer text-amber-700 hover:bg-amber-50" data-i18n="nav_apply_tutor">สมัครเป็นติวเตอร์</button>
                <button id="navBtnCreateCourse" onclick="openCreateCourseModal()" class="hidden whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-semibold transition cursor-pointer text-emerald-700 bg-emerald-50 hover:bg-emerald-100" data-i18n="nav_create_course">+ ลงขายคอร์สเรียน</button>
                <button onclick="openContactAdminModal('ติดต่อแอดมิน')" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer text-slate-600 hover:text-slate-900" data-i18n="nav_contact_admin">ติดต่อแอดมิน</button>
            </nav>

            <div class="flex items-center gap-2 shrink-0">
                <button onclick="toggleLanguage()" id="langSwitcherBtn" class="px-2.5 py-1.5 rounded-lg border border-slate-200 hover:border-academic-blue bg-slate-50 text-slate-700 text-xs font-bold transition cursor-pointer" title="Switch Language (TH/EN)">🌐 TH</button>
                <button onclick="openCartDrawer()" class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 hover:border-academic-blue bg-white text-slate-700 text-xs sm:text-sm font-medium transition cursor-pointer">
                    <svg class="w-4 h-4 text-academic-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="hidden sm:inline" data-i18n="nav_cart">ตะกร้า</span>
                    <span id="cartBadge" class="px-1.5 py-0.5 text-xs rounded-full bg-academic-blue text-white font-semibold leading-none">0</span>
                </button>
                <div id="guestActions" class="flex items-center gap-2">
                    <button onclick="openAuthModal('login')" class="whitespace-nowrap px-4 py-2 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-medium text-xs sm:text-sm transition cursor-pointer" data-i18n="nav_login">เข้าสู่ระบบ</button>
                </div>
                <div id="userActions" class="hidden items-center gap-1.5">
                    <button onclick="openProfileModal()" class="whitespace-nowrap inline-flex items-center gap-2 pl-1.5 pr-3 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-medium transition cursor-pointer border border-slate-200" title="คลิกเพื่อแก้ไขโปรไฟล์">
                        <img id="navUserAvatar" src="" alt="Avatar" class="w-7 h-7 rounded-full object-cover border border-white shadow-2xs">
                        <span id="navUserName" class="max-w-[90px] sm:max-w-[120px] truncate">นักศึกษา</span>
                        <span id="navTutorBadge" class="hidden px-1.5 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-bold">TUTOR</span>
                    </button>
                    <button onclick="logout()" title="ออกจากระบบ" class="whitespace-nowrap px-2.5 py-2 rounded-lg border border-slate-200 hover:bg-rose-50 hover:text-rose-600 text-slate-500 text-xs font-medium transition cursor-pointer" data-i18n="nav_logout">ออก</button>
                </div>
            </div>
        </div>
        <div class="lg:hidden border-t border-slate-100 px-3 py-2 flex items-center justify-around bg-slate-50 text-xs font-medium overflow-x-auto gap-2">
            <button onclick="switchMainView('catalog')" class="whitespace-nowrap py-1 text-academic-blue font-semibold" data-i18n="nav_search_courses">ค้นหาคอร์สเรียน</button>
            <button onclick="switchMainView('my-courses')" class="whitespace-nowrap py-1 text-slate-600" data-i18n="nav_my_courses">คอร์สของฉัน</button>
            <button id="mobileBtnTutorAction" onclick="handleTutorActionClick()" class="whitespace-nowrap py-1 text-amber-700 font-semibold" data-i18n="nav_apply_tutor">สมัครเป็นติวเตอร์</button>
            <button onclick="openContactAdminModal('ติดต่อแอดมิน')" class="whitespace-nowrap py-1 text-slate-600" data-i18n="nav_contact_admin">ติดต่อแอดมิน</button>
        </div>
    </header>

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
                                        <span id="detailCoverSku" class="px-2.5 py-1 rounded bg-white/20 backdrop-blur-xs text-xs font-bold">259104</span>
                                    </div>
                                    <div class="space-y-1">
                                        <span id="detailCoverExamType" class="text-[11px] uppercase tracking-wider text-sky-300 font-semibold block">Midterm + Final</span>
                                        <h4 id="detailCoverTitle" class="text-sm sm:text-base font-bold leading-snug line-clamp-2"></h4>
                                        <p id="detailCoverTutor" class="text-xs text-slate-300 pt-1 border-t border-white/20"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full grid grid-cols-2 gap-3 pt-1 text-center">
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center">
                                <svg class="w-7 h-7 text-academic-blue mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><circle cx="12" cy="13" r="8"/><path stroke-linecap="round" d="M12 9v4l2.5 2.5M9 2h6"/></svg>
                                <span id="detailStatHours" class="text-sm font-bold text-slate-800">20 ชั่วโมง</span>
                                <span class="text-xs text-slate-500" data-i18n="label_course_hours">ชั่วโมงคอร์ส</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col items-center">
                                <svg class="w-7 h-7 text-academic-blue mb-1" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                                <span id="detailStatDuration" class="text-sm font-bold text-slate-800">12 เดือน</span>
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

    <!-- VIEW 3: MY COURSES -->
    <div id="viewMyCourses" class="hidden flex-1">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img id="myCoursesAvatar" src="" alt="Profile" class="w-14 h-14 rounded-full object-cover border-2 border-academic-blue">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 id="myCoursesUserName" class="text-lg sm:text-xl font-bold text-slate-900"></h1>
                            <span id="myCoursesRoleBadge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700"></span>
                        </div>
                        <p id="myCoursesUserDept" class="text-xs sm:text-sm text-slate-500"></p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <button onclick="openProfileModal()" class="px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs sm:text-sm transition cursor-pointer" data-i18n="btn_edit_profile">⚙️ แก้ไขโปรไฟล์ / รูปภาพ</button>
                    <button id="myCoursesActionBtn" onclick="handleTutorActionClick()" class="px-4 py-2 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs sm:text-sm transition cursor-pointer">สมัครเป็นติวเตอร์</button>
                </div>
            </div>

            <div class="border-b border-slate-200 flex items-center gap-6 overflow-x-auto">
                <button id="subTabPurchased" onclick="switchMyCoursesSubTab('purchased')" class="whitespace-nowrap pb-3 border-b-2 border-academic-blue text-academic-blue font-bold text-sm sm:text-base transition cursor-pointer">📚 คอร์สที่ฉันซื้อแล้ว (0)</button>
                <button id="subTabUploaded" onclick="switchMyCoursesSubTab('uploaded')" class="whitespace-nowrap pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-medium text-sm sm:text-base transition cursor-pointer">📹 คลิปสอนที่ฉันลงขาย (0)</button>
            </div>

            <div id="purchasedCoursesPanel" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
            <div id="uploadedCoursesPanel" class="hidden grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs sm:text-sm text-slate-500">
            <div class="flex items-center gap-2.5">
                <span class="w-6 h-6 rounded bg-academic-blue text-white font-bold flex items-center justify-center text-xs">E</span>
                <span class="font-bold text-slate-800">EngTutor Academy</span>
                <span>— Engineering Peer-to-Peer Course Platform</span>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="openContactAdminModal('ติดต่อแอดมิน')" class="text-academic-blue hover:underline font-medium cursor-pointer" data-i18n="nav_contact_admin">ติดต่อแอดมิน</button>
                <span>Database Project</span>
            </div>
        </div>
    </footer>

    <!-- MODAL 1: Profile -->
    <div id="profileModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200 my-auto">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <h3 class="font-bold text-base" data-i18n="modal_profile_title">แก้ไขโปรไฟล์ของฉัน</h3>
                <button onclick="closeProfileModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            <form onsubmit="handleUpdateProfile(event)" class="p-6 space-y-4 text-sm">
                <div class="flex flex-col items-center space-y-2.5">
                    <img id="profilePreviewAvatar" src="" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-blue-100 shadow-sm">
                    <label class="px-3.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs cursor-pointer transition">
                        <span>📷 เปลี่ยนรูปโปรไฟล์</span>
                        <input id="profileAvatarFile" type="file" accept="image/*" onchange="previewProfileAvatar(event)" class="hidden">
                    </label>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อที่ใช้แสดง / ชื่อติวเตอร์ (นามแฝง) *</label>
                    <input id="profileName" type="text" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none font-semibold">
                </div>
                <div id="profileRealNameRow" class="hidden">
                    <label class="block text-xs font-semibold text-slate-500 mb-1">ชื่อ-นามสกุลจริง (ข้อมูลยืนยันตัวตนกับแอดมิน)</label>
                    <input id="profileRealName" type="text" disabled class="w-full px-3.5 py-2 rounded-lg border border-slate-200 bg-slate-100 text-slate-500 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">สาขาวิชาที่เรียน *</label>
                    <select id="profileDepartment" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none bg-white">
                        <option value="สาขาวิศวกรรมคอมพิวเตอร์">สาขาวิศวกรรมคอมพิวเตอร์</option>
                        <option value="สาขาวิศวกรรมโยธา">สาขาวิศวกรรมโยธา</option>
                        <option value="สาขาวิศวกรรมไฟฟ้า">สาขาวิศวกรรมไฟฟ้า</option>
                        <option value="สาขาวิศวกรรมเครื่องกล">สาขาวิศวกรรมเครื่องกล</option>
                        <option value="สาขาวิศวกรรมอุตสาหการ">สาขาวิศวกรรมอุตสาหการ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">เบอร์โทรศัพท์ติดต่อ</label>
                    <input id="profilePhone" type="text" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeProfileModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer">ยกเลิก</button>
                    <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">บันทึกโปรไฟล์</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: Tutor Application -->
    <div id="tutorApplicationModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-xl w-full overflow-hidden shadow-2xl border border-slate-200 my-auto max-h-[92vh] flex flex-col">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-amber-400">TUTOR REGISTRATION</span>
                    <h3 class="font-bold text-base">สมัครและยืนยันตัวตนเพื่อเป็นติวเตอร์</h3>
                </div>
                <button onclick="closeTutorApplicationModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            <form onsubmit="handleTutorApplication(event)" class="p-6 overflow-y-auto space-y-4 flex-1 text-sm">
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 space-y-1">
                    <p>กรอกชื่อ-นามสกุลจริงสำหรับให้แอดมินตรวจสอบ และตั้ง <strong>ชื่อติวเตอร์ (นามแฝง)</strong> สำหรับใช้แสดงให้ผู้ซื้อเห็นบนหน้าคอร์สเรียน</p>
                    <button type="button" onclick="fillSampleTutorVerification()" class="underline font-bold text-academic-blue cursor-pointer">⚡ คลิกเพื่อกรอกข้อมูลตัวอย่างอัตโนมัติ</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อ-นามสกุลจริง (สำหรับแอดมินตรวจสอบ) *</label>
                        <input id="tutorRealName" type="text" required placeholder="เช่น นายธิษณ์ธาวิน คงนา" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อติวเตอร์ / นามแฝงที่ใช้โชว์หน้าเว็บ *</label>
                        <input id="tutorAliasName" type="text" required placeholder="เช่น เสี่ยไอซ์" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none font-bold text-academic-blue">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสนักศึกษา *</label>
                        <input id="tutorStudentId" type="text" required placeholder="670610xxx" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">สาขาวิชาที่เรียน *</label>
                        <select id="tutorDepartment" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none bg-white text-xs sm:text-sm">
                            <option value="สาขาวิศวกรรมคอมพิวเตอร์">สาขาวิศวกรรมคอมพิวเตอร์</option>
                            <option value="สาขาวิศวกรรมโยธา">สาขาวิศวกรรมโยธา</option>
                            <option value="สาขาวิศวกรรมไฟฟ้า">สาขาวิศวกรรมไฟฟ้า</option>
                            <option value="สาขาวิศวกรรมเครื่องกล">สาขาวิศวกรรมเครื่องกล</option>
                            <option value="สาขาวิศวกรรมอุตสาหการ">สาขาวิศวกรรมอุตสาหการ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">เกรดเฉลี่ย (GPAX) *</label>
                        <input id="tutorGpax" type="number" step="0.01" min="2.00" max="4.00" required placeholder="3.65" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">เบอร์โทรศัพท์ติดต่อ *</label>
                        <input id="tutorPhone" type="text" required placeholder="089-xxx-xxxx" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ลิงก์ใบแสดงผลการเรียน (Transcript URL) *</label>
                        <input id="tutorTranscriptUrl" type="url" required placeholder="https://drive.google.com/..." class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">อัปโหลดรูปบัตรนักศึกษา (ไฟล์รูปภาพ)</label>
                    <input id="tutorIdCardFile" type="file" accept="image/*" class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-academic-blue file:text-white file:font-semibold">
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeTutorApplicationModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer">ยกเลิก</button>
                    <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">✓ ยืนยันตัวตนเป็นติวเตอร์</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: Create Course -->
    <div id="createCourseModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl max-w-2xl w-full overflow-hidden shadow-2xl border border-slate-200 my-auto max-h-[92vh] flex flex-col">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">CREATE NEW COURSE</span>
                    <h3 class="font-bold text-base">ลงขายคอร์สเรียนใหม่</h3>
                </div>
                <button onclick="closeCreateCourseModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            <form onsubmit="handleCreateCourse(event)" class="p-6 overflow-y-auto space-y-4 flex-1 text-sm">
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-900 flex items-start justify-between gap-3">
                    <div>
                        <strong>🔒 ข้อกำหนดการลงขาย:</strong> ระบบจะแสดงชื่อผู้สอนเป็น <strong id="createCourseTutorPreview" class="text-academic-blue underline"></strong> โดยอัตโนมัติ และหลังจากกดวางขายแล้ว <strong>ผู้สอนจะไม่สามารถแก้ไขราคาหรือข้อมูลเองได้ (ต้องแจ้งแอดมินเป็นผู้แก้ไขให้เท่านั้น)</strong>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">รหัสวิชา 6 หลัก (ระบบจัดหมวดสาขาให้อัตโนมัติ) *</label>
                        <input id="newCourseSku" type="text" required pattern="\d{6}" maxlength="6" oninput="updateAutoCategoryBadge(this.value)" placeholder="เช่น 261342 หรือ 259104" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none font-bold text-academic-blue">
                        <span id="autoDetectedCategoryText" class="text-[11px] text-emerald-700 font-medium block mt-1">พิมพ์รหัส 6 หลัก เช่น 261xxx = วิศวกรรมคอมพิวเตอร์</span>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">รูปแบบเนื้อหา *</label>
                        <select id="newCourseExamType" required class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none bg-white">
                            <option value="Midterm + Final">Midterm + Final (ครบทั้งเทอม)</option>
                            <option value="Midterm">ติวเข้มสอบ Midterm</option>
                            <option value="Final">ติวเข้มสอบ Final</option>
                            <option value="Full Course">ตะลุยโจทย์ข้อสอบเก่า & Lab</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ชื่อวิชาและชื่อคอร์สเรียน *</label>
                    <input id="newCourseName" type="text" required placeholder="เช่น Database Systems (สรุปเข้ม ER Diagram และ SQL)" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ราคาที่ต้องการขาย (บาท) *</label>
                        <input id="newCoursePrice" type="number" required min="0" step="1" placeholder="เช่น 490" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none font-bold text-academic-blue">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ชั่วโมงคอร์ส (กรอกเฉพาะตัวเลข) *</label>
                        <div class="relative">
                            <input id="newCourseHoursNumber" type="number" required min="0.5" max="500" step="0.5" placeholder="เช่น 20" class="w-full pl-3.5 pr-16 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none font-bold">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-semibold">ชั่วโมง</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">อายุคอร์สเรียน *</label>
                        <select id="newCourseDuration" class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none bg-white">
                            <option value="12 เดือน">12 เดือน</option>
                            <option value="6 เดือน">6 เดือน</option>
                            <option value="ตลอดชีพ">ตลอดชีพ</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">เหมาะสำหรับ *</label>
                    <input id="newCourseTarget" type="text" required placeholder="เช่น นักศึกษาชั้นปีที่ 2-3 ที่เรียนวิชานี้และต้องการปูพื้นฐานเพื่อทำข้อสอบ" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">รายละเอียดคอร์สเรียน *</label>
                    <textarea id="newCourseDesc" rows="2" required placeholder="อธิบายเนื้อหาที่จะสอนในคอร์สนี้..." class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">หัวข้อบทเรียน (ขึ้นบรรทัดใหม่ในแต่ละบท) *</label>
                    <textarea id="newCourseSyllabus" rows="3" required placeholder="บทที่ 1: ปูพื้นฐานสำคัญ&#10;บทที่ 2: ตะลุยโจทย์ข้อสอบเก่า" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
                </div>
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                        <div class="sm:col-span-8">
                            <label class="block text-xs font-semibold text-slate-700 mb-1">อัปโหลดไฟล์รูปปกคอร์สเรียน *</label>
                            <input id="newCourseCoverFile" type="file" accept="image/*" required onchange="previewCourseCover(event)" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-academic-blue file:text-white file:font-semibold cursor-pointer">
                        </div>
                        <div class="sm:col-span-4 flex justify-center">
                            <div class="w-24 h-16 rounded-lg border border-slate-300 bg-white overflow-hidden flex items-center justify-center text-[11px] text-slate-400">
                                <img id="coverPreviewImg" src="" class="hidden w-full h-full object-cover">
                                <span id="coverPreviewPlaceholder">ตัวอย่างปก</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">ลิงก์ดาวน์โหลดเอกสารการเรียน (ไฟล์ PDF) *</label>
                        <input id="newCoursePdfUrl" type="url" required placeholder="https://drive.google.com/..." class="w-full px-3.5 py-2 rounded-lg border border-slate-300 bg-white focus:border-academic-blue focus:outline-none">
                    </div>
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeCreateCourseModal()" class="w-1/3 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-semibold cursor-pointer">ยกเลิก</button>
                    <button type="submit" class="w-2/3 py-2.5 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">ยืนยันและวางขายคอร์สเรียน</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: Contact Tutor -->
    <div id="contactTutorModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200">
            <div class="px-6 py-4 bg-academic-blue text-white flex items-center justify-between">
                <h3 class="font-bold text-base">💬 ติดต่อผู้สอนคอร์สเรียน</h3>
                <button onclick="closeContactTutorModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            <form onsubmit="handleSendTutorMessage(event)" class="p-6 space-y-4 text-sm">
                <div class="p-3.5 rounded-xl bg-blue-50 border border-blue-200 space-y-1 text-xs">
                    <div class="font-bold text-slate-900 text-sm" id="contactTutorNameDisplay">ผู้สอน: เสี่ยไอซ์</div>
                    <div class="text-academic-blue font-semibold" id="contactTutorDeptDisplay">สาขาวิศวกรรมคอมพิวเตอร์</div>
                    <div class="text-slate-500" id="contactTutorCourseDisplay">วิชา: 261342</div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ข้อความหรือคำถามถึงผู้สอน *</label>
                    <textarea id="tutorMessageInput" rows="3" required placeholder="สอบถามรายละเอียดเนื้อหา แนวข้อสอบ หรือนัดหมายถามการบ้าน..." class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeContactTutorModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer">ปิด</button>
                    <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">ส่งข้อความถึงผู้สอน</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 5: Contact Admin -->
    <div id="contactAdminModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <h3 class="font-bold text-base">🛠️ ติดต่อแอดมินผู้ดูแลระบบ (Admin Support)</h3>
                <button onclick="closeContactAdminModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            <form onsubmit="handleSendAdminMessage(event)" class="p-6 space-y-4 text-sm">
                <div class="p-3 rounded-xl bg-slate-100 text-xs text-slate-600 leading-relaxed">
                    สำหรับการแจ้งแก้ไขราคาคอร์ส แก้ไขรายละเอียดคอร์สที่วางขายแล้ว การขอจัดโปรโมชันลดราคา หรือแจ้งปัญหาการชำระเงิน (ดำเนินการโดยแอดมินเท่านั้น)
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">หัวข้อที่ต้องการติดต่อแอดมิน *</label>
                    <input id="adminTopicInput" type="text" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">รายละเอียดที่ต้องการให้แอดมินดำเนินการ *</label>
                    <textarea id="adminMessageInput" rows="3" required placeholder="ระบุรหัสวิชา รายละเอียดที่ต้องการแก้ไข หรือปัญหาที่พบ..." class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeContactAdminModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer">ยกเลิก</button>
                    <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-semibold cursor-pointer">ส่งเรื่องให้แอดมิน</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 6: Video Player -->
    <div id="videoPlayerModal" class="fixed inset-0 z-50 hidden bg-slate-900/70 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-3xl w-full overflow-hidden shadow-2xl border border-slate-200">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <span id="playerCourseSku" class="text-xs font-bold text-sky-400">รหัสวิชา</span>
                    <h3 id="playerCourseTitle" class="font-bold text-base">กำลังเล่นวิดีโอคอร์สเรียน</h3>
                </div>
                <button onclick="closeVideoPlayerModal()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            <div class="p-6 space-y-5">
                <div class="aspect-video rounded-xl bg-slate-950 flex flex-col items-center justify-center text-white p-6 text-center">
                    <div class="w-14 h-14 rounded-full bg-academic-blue flex items-center justify-center text-xl shadow-lg mb-3">▶</div>
                    <p class="font-bold text-base sm:text-lg">วิดีโอคอร์สเรียน (Full HD 1080p)</p>
                    <p id="playerTutorName" class="text-xs text-slate-400 mt-1"></p>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-4 bg-blue-50/70 p-4 rounded-xl border border-blue-200">
                    <div class="text-xs sm:text-sm">
                        <p class="font-bold text-slate-900">📄 เอกสารประกอบการเรียน (PDF)</p>
                        <p id="playerPdfLinkText" class="text-slate-600 text-xs truncate max-w-xs sm:max-w-md"></p>
                    </div>
                    <a id="playerPdfDownloadAnchor" href="#" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs sm:text-sm transition">
                        📥 เปิดลิงก์ดาวน์โหลด PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- DRAWER: Cart -->
    <div id="cartDrawer" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs justify-end">
        <div class="bg-white w-full max-w-md h-full shadow-2xl flex flex-col justify-between">
            <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base" data-i18n="nav_cart">ตะกร้าคอร์สเรียน</h3>
                    <p class="text-xs text-slate-300 mt-0.5">สามารถกรอกโค้ดส่วนลดเพิ่มได้ (โค้ดแต่ละรหัสใช้ได้ 1 ครั้ง)</p>
                </div>
                <button onclick="closeCartDrawer()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            <div id="cartItemsContainer" class="flex-1 p-5 overflow-y-auto space-y-3"></div>
            <div class="p-5 bg-slate-50 border-t border-slate-200 space-y-4">
                <div id="appliedCouponsBadges" class="hidden flex-wrap items-center gap-1.5"></div>
                <form onsubmit="handleApplyCoupon(event)" class="flex gap-2">
                    <input id="couponInput" type="text" placeholder="กรอกโค้ดส่วนลด เช่น ENGMID20 หรือ PASSA100" class="flex-1 px-3.5 py-2 rounded-lg bg-white border border-slate-300 text-sm font-semibold uppercase focus:outline-none focus:border-academic-blue">
                    <button id="couponSubmitBtn" type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs sm:text-sm transition cursor-pointer shrink-0">ใช้โค้ด</button>
                </form>
                <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-1.5">
                    <div id="originalTotalRow" class="hidden justify-between text-xs text-slate-500">
                        <span>ราคารวมปกติ</span>
                        <span id="cartOriginalTotalAmount">0 บาท</span>
                    </div>
                    <div id="discountRow" class="hidden justify-between text-xs text-emerald-600 font-semibold">
                        <span id="discountLabelText">ส่วนลดคูปองรวม</span>
                        <span id="cartDiscountAmount">-0 บาท</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-sm font-semibold text-slate-600">ยอดชำระสุทธิ:</span>
                        <span id="cartTotalAmount" class="text-xl font-bold text-academic-blue">0 บาท</span>
                    </div>
                </div>
                <button onclick="handleCheckout()" class="w-full py-3 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-sm sm:text-base shadow-sm transition cursor-pointer">ยืนยันการชำระเงิน</button>
            </div>
        </div>
    </div>

    <!-- MODAL: Auth (Login/Register) -->
    <div id="authModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200">
            <div class="grid grid-cols-2 bg-slate-100 p-1 m-4 rounded-xl">
                <button id="tabLoginBtn" onclick="switchAuthTab('login')" class="py-2 rounded-lg font-semibold text-sm transition cursor-pointer bg-white text-academic-blue shadow-xs">เข้าสู่ระบบ</button>
                <button id="tabRegisterBtn" onclick="switchAuthTab('register')" class="py-2 rounded-lg font-semibold text-sm transition cursor-pointer text-slate-600">สมัครสมาชิก</button>
            </div>
            <div class="px-6 pb-6 pt-1 text-sm">
                <form id="loginForm" onsubmit="handleLogin(event)" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">อีเมลนักศึกษา</label>
                        <input id="loginEmail" type="email" required value="test@example.com" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">รหัสผ่าน</label>
                        <input id="loginPassword" type="password" required value="password123" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg text-xs text-slate-700 border border-blue-200 space-y-1">
                        <div><strong>บัญชีนักศึกษาทั่วไป:</strong> <code class="text-academic-blue font-bold">test@example.com</code></div>
                        <div><strong>บัญชีติวเตอร์ ("เสี่ยไอซ์"):</strong> <code class="text-emerald-700 font-bold">tutor@example.com</code></div>
                        <div>รหัสผ่าน: <code class="font-bold">password123</code></div>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="closeAuthModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer">ยกเลิก</button>
                        <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">เข้าสู่ระบบ</button>
                    </div>
                </form>

                <form id="registerForm" onsubmit="handleRegister(event)" class="hidden space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">ชื่อที่ใช้แสดง</label>
                        <input id="regName" type="text" required placeholder="เช่น กิตติพงศ์" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">อีเมล</label>
                        <input id="regEmail" type="email" required placeholder="student@cmu.ac.th" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">รหัสผ่าน (อย่างน้อย 6 ตัวอักษร)</label>
                        <input id="regPassword" type="password" required minlength="6" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">สาขาวิชาที่เรียน</label>
                        <select id="regAddress" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none bg-white">
                            <option value="สาขาวิศวกรรมคอมพิวเตอร์">สาขาวิศวกรรมคอมพิวเตอร์</option>
                            <option value="สาขาวิศวกรรมโยธา">สาขาวิศวกรรมโยธา</option>
                            <option value="สาขาวิศวกรรมไฟฟ้า">สาขาวิศวกรรมไฟฟ้า</option>
                            <option value="สาขาวิศวกรรมเครื่องกล">สาขาวิศวกรรมเครื่องกล</option>
                            <option value="สาขาวิศวกรรมอุตสาหการ">สาขาวิศวกรรมอุตสาหการ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">เบอร์โทรศัพท์</label>
                        <input id="regPhone" type="text" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="closeAuthModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer">ยกเลิก</button>
                        <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer">สมัครสมาชิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastBox" class="fixed bottom-5 right-5 z-50 hidden max-w-sm px-5 py-3 rounded-xl shadow-lg text-white font-medium text-sm transition-all"></div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        let allCourses = [];
        let allCategories = [];
        let purchasedCourses = [];
        let purchasedCourseIds = new Set();
        let activeCategory = 'ALL';
        let searchQuery = '';
        let currentDetailCourse = null;
        
        let currentUser = null; 
        let currentLang = localStorage.getItem('app_lang') || 'th';

        const i18nDict = {
            th: { 
                nav_search_courses: 'ค้นหาคอร์สเรียน',
                nav_my_courses: 'คอร์สของฉัน',
                nav_apply_tutor: 'สมัครเป็นติวเตอร์',
                nav_create_course: '+ ลงขายคอร์สเรียน',
                nav_contact_admin: 'ติดต่อแอดมิน',
                nav_cart: 'ตะกร้า',
                nav_login: 'เข้าสู่ระบบ',
                nav_logout: 'ออก',
                hero_badge: 'แพลตฟอร์มคอร์สเรียนคณะวิศวกรรมศาสตร์',
                hero_title: 'ค้นหาคอร์สติวและสรุปเนื้อหาจากรหัสวิชา 6 หลัก',
                hero_subtitle: 'เรียนกับติวเตอร์รุ่นพี่ในคณะวิศวกรรมศาสตร์ที่ผ่านการยืนยันตัวตนและผลการเรียน พร้อมเอกสารประกอบการเรียน PDF',
                btn_search: 'ค้นหาวิชา',
                popular_codes: 'รหัสวิชายอดนิยม:',
                dept_filter_title: 'แยกตามสาขาวิชา (รหัสขึ้นต้น 3 หลัก)',
                btn_back_catalog: '← ย้อนกลับไปหน้ารวมคอร์สเรียน',
                label_course_hours: 'ชั่วโมงคอร์ส',
                label_course_duration: 'อายุคอร์สเรียน',
                label_suitable_for: 'เหมาะสำหรับ :',
                label_course_desc: 'รายละเอียดคอร์ส :',
                pdf_included_note: 'รวมลิงก์ดาวน์โหลดไฟล์เอกสารประกอบการเรียน (PDF) พร้อมเข้าเรียนทันทีหลังชำระเงิน',
                btn_contact_tutor: '💬 ติดต่อผู้สอน',
                section_syllabus: 'หัวข้อบทเรียนในคอร์ส (Syllabus)',
                section_instructor_reviews: 'ข้อมูลผู้สอนและรีวิวจากผู้เรียน',
                write_review_label: 'เขียนรีวิวให้คอร์สเรียนนี้:',
                btn_edit_profile: '⚙️ แก้ไขโปรไฟล์ / รูปภาพ',
                modal_profile_title: 'แก้ไขโปรไฟล์ของฉัน',
                unit_hours: 'ชั่วโมง',
                unit_baht: 'บาท',
                btn_detail: 'ดูรายละเอียด',
                btn_buy: 'สั่งซื้อ',
                btn_purchased_watch: '✓ ซื้อแล้ว (เข้าเรียน)',
                all_depts: 'ทุกสาขาวิชา'
            },
            en: { 
                nav_search_courses: 'Browse Courses',
                nav_my_courses: 'My Courses',
                nav_apply_tutor: 'Become a Tutor',
                nav_create_course: '+ Publish Course',
                nav_contact_admin: 'Contact Admin',
                nav_cart: 'Cart',
                nav_login: 'Sign In',
                nav_logout: 'Logout',
                hero_badge: 'Engineering Peer-to-Peer Learning Platform',
                hero_title: 'Search Engineering Courses by 6-Digit Course Code',
                hero_subtitle: 'Learn from verified top-performing engineering seniors with downloadable PDF study sheets and exam guides.',
                btn_search: 'Search',
                popular_codes: 'Popular Codes:',
                dept_filter_title: 'Filter by Engineering Department (3-Digit Prefix)',
                btn_back_catalog: '← Back to Course Directory',
                label_course_hours: 'Course Length',
                label_course_duration: 'Access Period',
                label_suitable_for: 'Suitable For :',
                label_course_desc: 'Course Description :',
                pdf_included_note: 'Includes downloadable PDF lecture notes & past exam solutions immediately after enrollment.',
                btn_contact_tutor: '💬 Contact Tutor',
                section_syllabus: 'Course Syllabus',
                section_instructor_reviews: 'Instructor & Student Reviews',
                write_review_label: 'Write a review for this course:',
                btn_edit_profile: '⚙️ Edit Profile / Avatar',
                modal_profile_title: 'Edit My Profile',
                unit_hours: 'Hours',
                unit_baht: 'THB',
                btn_detail: 'Details',
                btn_buy: 'Enroll',
                btn_purchased_watch: '✓ Enrolled (Watch)',
                all_depts: 'All Departments'
            }
        };

        function t(key) {
            return (i18nDict[currentLang] && i18nDict[currentLang][key]) || i18nDict.th[key] || key;
        }

        function toggleLanguage() {
            currentLang = currentLang === 'th' ? 'en' : 'th';
            localStorage.setItem('app_lang', currentLang);
            applyLanguageUI();
        }

        function applyLanguageUI() {
            document.getElementById('langSwitcherBtn').textContent = currentLang === 'th' ? '🌐 TH' : '🌐 EN';
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (key) el.textContent = t(key);
            });
            const searchInput = document.getElementById('courseCodeSearchInput');
            if (searchInput) {
                searchInput.placeholder = currentLang === 'th'
                    ? 'พิมพ์รหัสวิชา 6 หลัก เช่น 259104, 251201, 252211, 261342...'
                    : 'Enter 6-digit course code e.g. 259104, 261102, 261342...';
            }
            renderCategoryButtons();
            renderCourses();
            if (currentDetailCourse) openCourseDetailPage(currentDetailCourse.id, false);
            if (currentUser) renderMyCoursesPanels();
        }

        document.addEventListener('DOMContentLoaded', async () => {
            applyLanguageUI();
            await fetchCourses();
            
            await fetchMemberProfile();
            
            if (currentUser) {
                await fetchMyCoursesData();
                await fetchCart();
            }
        });

        function showToast(message, isError = false) {
            const toast = document.getElementById('toastBox');
            toast.textContent = message;
            toast.className = `fixed bottom-5 right-5 z-50 max-w-sm px-5 py-3 rounded-xl shadow-lg text-white font-medium text-sm transition-all ${
                isError ? 'bg-rose-600' : 'bg-slate-900'
            }`;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 3800);
        }

        function copyCoupon(code) {
            navigator.clipboard?.writeText(code);
            const couponInput = document.getElementById('couponInput');
            if (couponInput && !couponInput.disabled) couponInput.value = code;
            showToast(`คัดลอกโค้ดส่วนลด ${code} เรียบร้อยแล้ว`);
        }

        function isCoursePurchased(courseId) {
            return purchasedCourseIds.has(Number(courseId));
        }

        function switchMainView(viewName) {
            const vCatalog = document.getElementById('viewCatalog');
            const vDetail = document.getElementById('viewCourseDetail');
            const vMyCourses = document.getElementById('viewMyCourses');
            const tabCatalog = document.getElementById('navTabCatalog');
            const tabMyCourses = document.getElementById('navTabMyCourses');

            vCatalog.classList.add('hidden');
            vDetail.classList.add('hidden');
            vMyCourses.classList.add('hidden');

            if (tabCatalog && tabMyCourses) {
                tabCatalog.className = 'whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer text-slate-600 hover:text-slate-900';
                tabMyCourses.className = 'whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer text-slate-600 hover:text-slate-900 flex items-center gap-1.5';
            }

            if (viewName === 'catalog') {
                vCatalog.classList.remove('hidden');
                if (tabCatalog) tabCatalog.className = 'whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer bg-white text-academic-blue shadow-xs';
                renderCourses();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (viewName === 'detail') {
                vDetail.classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (viewName === 'my-courses') {
                if (!currentUser) {
                    showToast('กรุณาเข้าสู่ระบบเพื่อดูคอร์สเรียนของคุณ', true);
                    openAuthModal('login');
                    vCatalog.classList.remove('hidden');
                    return;
                }
                vMyCourses.classList.remove('hidden');
                if (tabMyCourses) tabMyCourses.className = 'whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer bg-white text-academic-blue shadow-xs flex items-center gap-1.5';
                fetchMyCoursesData();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function parseCourseMeta(course) {
            const v = (course.variants && course.variants.length > 0) ? course.variants[0] : null;
            let tutorName = 'เสี่ยไอซ์';
            let ownerEmail = '';
            let hoursNum = 20;
            let duration = '12 เดือน';
            let tutorDepartment = 'สาขาวิศวกรรมคอมพิวเตอร์';
            let examType = 'Midterm + Final';
            let suitableFor = `นักศึกษาคณะวิศวกรรมศาสตร์ที่ลงเรียนรหัสวิชา ${course.sku || ''}`;
            let syllabus = "บทที่ 1: ปูพื้นฐานและสรุปทฤษฎีสำคัญ\nบทที่ 2: การวิเคราะห์โจทย์ตามแนวข้อสอบ\nบทที่ 3: ตะลุยข้อสอบเก่าพร้อมเฉลยละเอียด";
            let pdfUrl = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';

            if (v && v.size) {
                const parts = v.size.split('| OWNER:');
                tutorName = parts[0].trim();
                ownerEmail = parts[1] ? parts[1].trim() : '';
            }

            if (v && v.color) {
                try {
                    const parsed = JSON.parse(v.color);
                    if (parsed.hours_num) {
                        hoursNum = parsed.hours_num;
                    } else if (parsed.hours) {
                        hoursNum = parseFloat(parsed.hours) || 20;
                    }
                    duration = parsed.duration || duration;
                    tutorDepartment = parsed.tutor_department || tutorDepartment;
                    examType = parsed.exam_type || examType;
                    suitableFor = parsed.suitable_for || suitableFor;
                    syllabus = parsed.syllabus || syllabus;
                    pdfUrl = parsed.pdf_url || pdfUrl;
                } catch (e) {}
            }

            if (currentUser && ownerEmail === currentUser.email) {
                tutorName = (currentUser.meta && currentUser.meta.tutor_alias) ? currentUser.meta.tutor_alias : currentUser.name;
                tutorDepartment = (currentUser.meta && currentUser.meta.department) ? currentUser.meta.department : (currentUser.address || tutorDepartment);
            }

            const hoursFormatted = `${hoursNum} ${t('unit_hours')}`;
            return { tutorName, ownerEmail, hoursNum, hoursFormatted, duration, tutorDepartment, examType, suitableFor, syllabus, pdfUrl };
        }

        function extractPrefixFromCategoryName(catName) {
            if (!catName) return '';
            const match = catName.match(/(\d{3})xxx/);
            return match ? match[1] : '';
        }

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
                labelEl.className = 'text-[11px] text-emerald-700 font-semibold block mt-1';
            } else {
                // แจ้งว่ารหัสวิชานี้จะถูกจัดเข้าหมวดหมู่อื่นๆ
                labelEl.textContent = 'ระบบจะจัดเข้า "หมวดหมู่อื่นๆ" อัตโนมัติ';
                labelEl.className = 'text-[11px] text-amber-600 font-semibold block mt-1';
            }
        }

        async function fetchCourses() {
            try {
                const res = await fetch('/api/products', { 
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include'
                });
                const json = await res.json();
                allCourses = json.data || [];
                allCategories = json.categories || [];
                renderCategoryButtons();
                renderCourses();
                updateMyCoursesBadge();
            } catch (err) {
                document.getElementById('courseCountText').textContent = 'กรุณารัน php artisan migrate:fresh --seed';
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
            if (searchQuery.length > 0) clearBtn.classList.remove('hidden');
            else clearBtn.classList.add('hidden');
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

            document.getElementById('courseCountText').textContent = currentLang === 'th'
                ? `แสดง ${filtered.length} คอร์สเรียน`
                : `Showing ${filtered.length} courses`;

            if (filtered.length === 0) {
                const codeText = searchQuery || activeCategory;
                grid.innerHTML = `
                    <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 max-w-xl mx-auto space-y-3">
                        <div class="w-11 h-11 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-lg mx-auto font-bold">!</div>
                        <h3 class="text-base sm:text-lg font-bold text-slate-900">ยังไม่มีคอร์สเรียนในรหัสวิชา "${codeText}"</h3>
                        <p class="text-xs sm:text-sm text-slate-500">หากคุณเรียนผ่านวิชานี้แล้ว สามารถสมัครเป็นติวเตอร์เพื่อเปิดสอนวิชานี้ได้</p>
                        <div class="flex justify-center gap-3 pt-2">
                            <button onclick="setQuickSearch('')" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-xs font-semibold cursor-pointer">ดูคอร์สทั้งหมด</button>
                            <button onclick="handleTutorActionClick('${searchQuery.replace(/'/g, "\\'")}')" class="px-4 py-2 rounded-lg bg-academic-blue text-white text-xs font-semibold cursor-pointer">
                                ${currentUser && currentUser.role === 'tutor' ? '+ ลงขายคอร์สวิชานี้' : 'สมัครเป็นติวเตอร์'}
                            </button>
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
                const purchased = isCoursePurchased(course.id);

                return `
                    <article class="bg-white rounded-2xl border border-slate-200 hover:border-academic-blue/60 shadow-2xs hover:shadow-md transition flex flex-col overflow-hidden">
                        <div onclick="openCourseDetailPage(${course.id})" class="relative aspect-16/10 bg-slate-900 overflow-hidden cursor-pointer group">
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
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <div class="text-xs text-academic-blue font-semibold">${course.category ? course.category.name : ''}</div>
                                <h3 onclick="openCourseDetailPage(${course.id})" class="text-base font-bold text-slate-900 hover:text-academic-blue transition line-clamp-2 leading-snug cursor-pointer">
                                    ${course.name}
                                </h3>
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

        function openCourseDetailPage(courseId, switchView = true) {
            const course = allCourses.find(c => c.id === courseId);
            if (!course) return;
            currentDetailCourse = course;

            const meta = parseCourseMeta(course);
            const priceNum = Number(course.price);
            const purchased = isCoursePurchased(course.id);
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

        async function fetchMyCoursesData() {
            if (!currentUser) return;
            try {
                const res = await fetch('/api/my-courses', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include'
                });
                if (res.ok) {
                    const data = await res.json();
                    purchasedCourses = data.purchased_courses || [];
                    purchasedCourseIds = new Set((data.purchased_ids || []).map(id => Number(id)));
                    updateMyCoursesBadge();
                    renderCourses();
                    renderMyCoursesPanels();
                }
            } catch (err) {
                console.error(err);
            }
        }

        function getMyUploadedCourses() {
            if (!currentUser || currentUser.role !== 'tutor') return [];
            return allCourses.filter(c => {
                const meta = parseCourseMeta(c);
                return meta.ownerEmail === currentUser.email;
            });
        }

        function updateMyCoursesBadge() {
            const badge = document.getElementById('myCoursesCountBadge');
            if (!badge) return;
            const uploaded = getMyUploadedCourses();
            badge.textContent = purchasedCourses.length + uploaded.length;
        }

        function switchMyCoursesSubTab(tab) {
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
                panelUploaded.classList.remove('grid');
            } else {
                btnUploaded.className = 'whitespace-nowrap pb-3 border-b-2 border-academic-blue text-academic-blue font-bold text-sm sm:text-base transition cursor-pointer';
                btnPurchased.className = 'whitespace-nowrap pb-3 border-b-2 border-transparent text-slate-500 hover:text-slate-800 font-medium text-sm sm:text-base transition cursor-pointer';
                panelUploaded.classList.remove('hidden');
                panelUploaded.classList.add('grid');
                panelPurchased.classList.add('hidden');
                panelPurchased.classList.remove('grid');
            }
        }

        function renderMyCoursesPanels() {
            const panelPurchased = document.getElementById('purchasedCoursesPanel');
            const panelUploaded = document.getElementById('uploadedCoursesPanel');
            const uploadedCourses = getMyUploadedCourses();

            document.getElementById('subTabPurchased').textContent = `📚 คอร์สที่ฉันซื้อแล้ว (${purchasedCourses.length})`;
            document.getElementById('subTabUploaded').textContent = `📹 คลิปสอนที่ฉันลงขาย (${uploadedCourses.length})`;

            if (purchasedCourses.length === 0) {
                panelPurchased.innerHTML = `
                    <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 space-y-3">
                        <p class="text-base font-bold text-slate-800">คุณยังไม่มีคอร์สเรียนที่ซื้อไว้</p>
                        <button onclick="switchMainView('catalog')" class="px-5 py-2 rounded-lg bg-academic-blue text-white text-xs font-semibold cursor-pointer">ไปหน้าค้นหาคอร์สเรียน</button>
                    </div>
                `;
            } else {
                panelPurchased.innerHTML = purchasedCourses.map(course => {
                    const imgUrl = (course.images && course.images.length > 0)
                        ? course.images[0].image_url
                        : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';
                    const meta = parseCourseMeta(course);

                    return `
                        <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col sm:flex-row gap-5 items-start sm:items-center justify-between shadow-2xs">
                            <div class="flex items-start gap-4">
                                <img src="${imgUrl}" alt="${course.name}" class="w-24 h-24 rounded-xl object-cover shrink-0 border border-slate-200">
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
                                <button
                                    onclick="openVideoPlayerModal(${course.id})"
                                    class="flex-1 sm:w-40 py-2.5 px-4 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-xs text-center transition cursor-pointer"
                                >
                                    ▶ เข้าเรียนวิดีโอ
                                </button>
                                <a
                                    href="${meta.pdfUrl}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex-1 sm:w-40 py-2 px-4 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs text-center transition"
                                >
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
                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold">สำหรับผู้ต้องการเปิดสอน</span>
                        <p class="text-base font-bold text-slate-900">บัญชีของคุณยังไม่ได้ลงทะเบียนยืนยันตัวตนเป็นติวเตอร์</p>
                        <p class="text-xs text-slate-600 max-w-md mx-auto">กรุณาสมัครและยืนยันตัวตนด้วยชื่อ-นามสกุลจริงและรหัสนักศึกษาก่อน เพื่อเปิดสิทธิ์การลงขายคอร์สเรียน</p>
                        <button onclick="openTutorApplicationModal()" class="px-6 py-2.5 rounded-xl bg-academic-blue text-white text-xs sm:text-sm font-semibold cursor-pointer">
                            🎓 สมัครและยืนยันตัวตนเป็นติวเตอร์
                        </button>
                    </div>
                `;
            } else if (uploadedCourses.length === 0) {
                panelUploaded.innerHTML = `
                    <div class="col-span-full bg-white rounded-2xl p-10 text-center border border-slate-200 space-y-3">
                        <p class="text-base font-bold text-slate-800">คุณยังไม่มีคอร์สเรียนที่เปิดขาย</p>
                        <button onclick="openCreateCourseModal()" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold cursor-pointer">+ ลงขายคอร์สเรียนวิชาแรกของคุณ</button>
                    </div>
                `;
            } else {
                panelUploaded.innerHTML = uploadedCourses.map(course => {
                    const imgUrl = (course.images && course.images.length > 0)
                        ? course.images[0].image_url
                        : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80';
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
                                <p class="text-xs text-slate-500">⏱ ${meta.hoursFormatted} • ราคาขาย ${Number(course.price).toLocaleString()} บาท</p>
                                <div class="p-2.5 rounded-lg bg-slate-50 border border-slate-200 text-[11px] text-slate-500">
                                    🔒 คอร์สที่วางขายแล้วไม่สามารถแก้ไขเองได้ หากต้องการปรับราคาหรือเนื้อหา กรุณาแจ้งแอดมิน
                                </div>
                            </div>
                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                                <button
                                    onclick="openCourseDetailPage(${course.id})"
                                    class="flex-1 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition cursor-pointer"
                                >
                                    ดูหน้าคอร์ส
                                </button>
                                <button
                                    onclick="openContactAdminModal('แจ้งแก้ไขข้อมูลคอร์ส รหัสวิชา ${course.sku}')"
                                    class="flex-1 py-2 rounded-lg border border-academic-blue text-academic-blue hover:bg-blue-50 font-semibold text-xs transition cursor-pointer"
                                >
                                    🛠️ แจ้งแอดมินแก้ไข
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        }

        function openVideoPlayerModal(courseId) {
            const course = allCourses.find(c => c.id === courseId) || purchasedCourses.find(c => c.id === courseId);
            if (!course) return;
            const meta = parseCourseMeta(course);
            document.getElementById('playerCourseSku').textContent = `รหัสวิชา ${course.sku}`;
            document.getElementById('playerCourseTitle').textContent = course.name;
            document.getElementById('playerTutorName').textContent = `ผู้สอน: ${meta.tutorName} (${meta.tutorDepartment})`;
            document.getElementById('playerPdfDownloadAnchor').href = meta.pdfUrl;
            document.getElementById('playerPdfLinkText').textContent = meta.pdfUrl;
            document.getElementById('videoPlayerModal').classList.remove('hidden');
        }

        function closeVideoPlayerModal() {
            document.getElementById('videoPlayerModal').classList.add('hidden');
        }

        function openProfileModal() {
            if (!currentUser) return;
            const meta = currentUser.meta || {};
            document.getElementById('profilePreviewAvatar').src = meta.avatar_url || 'https://ui-avatars.com/api/?name=User&background=0066FF&color=fff';
            document.getElementById('profileName').value = meta.tutor_alias || currentUser.name;
            document.getElementById('profilePhone').value = currentUser.phone_number || '';
            document.getElementById('profileDepartment').value = meta.department || currentUser.address || 'สาขาวิศวกรรมคอมพิวเตอร์';

            const realNameRow = document.getElementById('profileRealNameRow');
            if (currentUser.role === 'tutor' && meta.real_name) {
                realNameRow.classList.remove('hidden');
                document.getElementById('profileRealName').value = meta.real_name;
            } else {
                realNameRow.classList.add('hidden');
            }

            document.getElementById('profileModal').classList.remove('hidden');
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
        }

        function previewProfileAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('profilePreviewAvatar').src = URL.createObjectURL(file);
            }
        }

        async function handleUpdateProfile(e) {
            e.preventDefault();
            const formData = new FormData();
            const aliasVal = document.getElementById('profileName').value.trim();
            formData.append('name', aliasVal);
            formData.append('tutor_alias', aliasVal);
            formData.append('department', document.getElementById('profileDepartment').value);
            formData.append('phone_number', document.getElementById('profilePhone').value.trim());

            const fileInput = document.getElementById('profileAvatarFile');
            if (fileInput.files.length > 0) {
                formData.append('avatar_image', fileInput.files[0]);
            }

            try {
                const res = await fetch('/api/profile/update', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData,
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถอัปเดตโปรไฟล์ได้', true);
                    return;
                }
                currentUser = data.user;
                updateAuthUI();
                renderCourses();
                renderMyCoursesPanels();
                closeProfileModal();
                showToast('✓ อัปเดตข้อมูลโปรไฟล์และรูปภาพเรียบร้อยแล้ว');
            } catch (err) {
                showToast('เกิดข้อผิดพลาดในการบันทึกโปรไฟล์', true);
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

        function openContactAdminModal(defaultTopic = 'ติดต่อแอดมิน') {
            document.getElementById('adminTopicInput').value = defaultTopic;
            document.getElementById('adminMessageInput').value = '';
            document.getElementById('contactAdminModal').classList.remove('hidden');
        }

        function closeContactAdminModal() {
            document.getElementById('contactAdminModal').classList.add('hidden');
        }

        function handleSendAdminMessage(e) {
            e.preventDefault();
            closeContactAdminModal();
            showToast('🛠️ ส่งเรื่องถึงแอดมินเรียบร้อยแล้ว แอดมินจะดำเนินการตรวจสอบและแก้ไขให้โดยเร็วที่สุด');
        }

        function handleTutorActionClick(prefillSku = '') {
            if (!currentUser) {
                showToast('กรุณาเข้าสู่ระบบก่อนดำเนินการ', true);
                openAuthModal('login');
                return;
            }
            if (currentUser && currentUser.role === 'tutor') {
                openCreateCourseModal(prefillSku);
            } else {
                openTutorApplicationModal();
            }
        }

        function openTutorApplicationModal() {
            if (!currentUser) {
                showToast('กรุณาเข้าสู่ระบบก่อนสมัครเป็นติวเตอร์', true);
                openAuthModal('login');
                return;
            }
            if (currentUser && currentUser.role === 'tutor') {
                openCreateCourseModal();
                return;
            }
            document.getElementById('tutorApplicationModal').classList.remove('hidden');
        }

        function closeTutorApplicationModal() {
            document.getElementById('tutorApplicationModal').classList.add('hidden');
        }

        function fillSampleTutorVerification() {
            document.getElementById('tutorRealName').value = 'ธิษณ์ธาวิน คงนา';
            document.getElementById('tutorAliasName').value = 'เสี่ยไอซ์';
            document.getElementById('tutorStudentId').value = '670610706';
            document.getElementById('tutorDepartment').value = 'สาขาวิศวกรรมคอมพิวเตอร์';
            document.getElementById('tutorGpax').value = '3.75';
            document.getElementById('tutorPhone').value = '089-456-7890';
            document.getElementById('tutorTranscriptUrl').value = 'https://drive.google.com/file/d/sample-transcript/view';
        }

        async function handleTutorApplication(e) {
            e.preventDefault();
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

            try {
                const res = await fetch('/api/apply-tutor', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData,
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบอีกครั้ง', true);
                    return;
                }
                currentUser = data.user;
                updateAuthUI();
                closeTutorApplicationModal();
                renderMyCoursesPanels();
                showToast(data.message || 'ยืนยันตัวตนเป็นติวเตอร์สำเร็จ!');
            } catch (err) {
                showToast('เกิดข้อผิดพลาดในการส่งคำขอสมัครติวเตอร์', true);
            }
        }

        function openCreateCourseModal(prefillSku = '') {
            if (!currentUser) {
                showToast('กรุณาเข้าสู่ระบบก่อนลงขายคอร์สเรียน', true);
                openAuthModal('login');
                return;
            }
            if (!currentUser || currentUser.role !== 'tutor') {
                showToast('กรุณาสมัครและยืนยันตัวตนเป็นติวเตอร์ก่อนลงขายคอร์สเรียน', true);
                openTutorApplicationModal();
                return;
            }
            if (prefillSku) {
                document.getElementById('newCourseSku').value = prefillSku;
                updateAutoCategoryBadge(prefillSku);
            }
            const alias = (currentUser.meta && currentUser.meta.tutor_alias) ? currentUser.meta.tutor_alias : currentUser.name;
            const dept = (currentUser.meta && currentUser.meta.department) ? currentUser.meta.department : (currentUser.address || 'สาขาวิศวกรรมคอมพิวเตอร์');
            document.getElementById('createCourseTutorPreview').textContent = `${alias} (${dept})`;
            document.getElementById('createCourseModal').classList.remove('hidden');
        }

        function closeCreateCourseModal() {
            document.getElementById('createCourseModal').classList.add('hidden');
        }

        function previewCourseCover(event) {
            const file = event.target.files[0];
            const img = document.getElementById('coverPreviewImg');
            const placeholder = document.getElementById('coverPreviewPlaceholder');
            if (file) {
                img.src = URL.createObjectURL(file);
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
        }

        async function handleCreateCourse(e) {
            e.preventDefault();
            const fileInput = document.getElementById('newCourseCoverFile');
            if (fileInput.files.length === 0) {
                showToast('กรุณาเลือกไฟล์รูปปกคอร์สเรียนจากเครื่องของคุณ', true);
                return;
            }

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
            formData.append('cover_image', fileInput.files[0]);

            try {
                const res = await fetch('/api/products', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData,
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถบันทึกคอร์สเรียนได้ กรุณาตรวจสอบข้อมูล', true);
                    return;
                }
                showToast(data.message || 'วางขายคอร์สเรียนสำเร็จ!');
                closeCreateCourseModal();
                await fetchCourses();
                switchMainView('my-courses');
                switchMyCoursesSubTab('uploaded');
            } catch (err) {
                showToast('เกิดข้อผิดพลาดในการอัปโหลดคอร์สเรียน', true);
            }
        }

        function openAuthModal(tab = 'login') {
            switchAuthTab(tab);
            document.getElementById('authModal').classList.remove('hidden');
        }

        function closeAuthModal() {
            document.getElementById('authModal').classList.add('hidden');
        }

        function switchAuthTab(tab) {
            const loginForm = document.getElementById('loginForm');
            const regForm = document.getElementById('registerForm');
            const loginBtn = document.getElementById('tabLoginBtn');
            const regBtn = document.getElementById('tabRegisterBtn');

            if (tab === 'login') {
                loginForm.classList.remove('hidden');
                regForm.classList.add('hidden');
                loginBtn.className = 'py-2 rounded-lg font-semibold text-sm transition cursor-pointer bg-white text-academic-blue shadow-xs';
                regBtn.className = 'py-2 rounded-lg font-semibold text-sm transition cursor-pointer text-slate-600';
            } else {
                loginForm.classList.add('hidden');
                regForm.classList.remove('hidden');
                regBtn.className = 'py-2 rounded-lg font-semibold text-sm transition cursor-pointer bg-white text-academic-blue shadow-xs';
                loginBtn.className = 'py-2 rounded-lg font-semibold text-sm transition cursor-pointer text-slate-600';
            }
        }

        async function handleLogin(e) {
            e.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            await performLogin(email, password);
        }

        async function performLogin(email, password) {
            try {
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password }),
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'อีเมลหรือรหัสผ่านไม่ถูกต้อง', true);
                    return;
                }
                
                currentUser = data.user;
                updateAuthUI();
                closeAuthModal();
                showToast(`เข้าสู่ระบบสำเร็จ ยินดีต้อนรับ ${currentUser.name}`);

                await fetchMyCoursesData();
                await fetchCart();
                renderCourses();

            } catch (err) {
                console.error(err);
                showToast('เกิดข้อผิดพลาดในการเข้าสู่ระบบ', true);
            }
        }

        async function handleRegister(e) {
            e.preventDefault();
            const payload = {
                name: document.getElementById('regName').value,
                email: document.getElementById('regEmail').value,
                password: document.getElementById('regPassword').value,
                phone_number: document.getElementById('regPhone').value,
                address: document.getElementById('regAddress').value,
            };

            try {
                const res = await fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถสมัครสมาชิกได้', true);
                    return;
                }
                await performLogin(payload.email, payload.password);
            } catch (err) {
                showToast('เกิดข้อผิดพลาดในการสมัครสมาชิก', true);
            }
        }

        async function fetchMemberProfile() {
            try {
                const res = await fetch('/api/profile', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include'
                });
                if (!res.ok) {
                    currentUser = null;
                    updateAuthUI();
                    return;
                }
                const data = await res.json();
                currentUser = data.user;
                updateAuthUI();
                updateMyCoursesBadge();
            } catch (err) {
                console.error(err);
            }
        }

        function updateAuthUI() {
            const guestDiv = document.getElementById('guestActions');
            const userDiv = document.getElementById('userActions');
            const btnApplyTutor = document.getElementById('navBtnApplyTutor');
            const btnCreateCourse = document.getElementById('navBtnCreateCourse');
            const navTutorBadge = document.getElementById('navTutorBadge');
            const myCoursesActionBtn = document.getElementById('myCoursesActionBtn');
            const myCoursesRoleBadge = document.getElementById('myCoursesRoleBadge');
            const mobileBtnTutor = document.getElementById('mobileBtnTutorAction');

            if (currentUser) {
                const meta = currentUser.meta || {};
                const avatarUrl = meta.avatar_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(currentUser.name) + '&background=0066FF&color=fff';
                const displayName = meta.tutor_alias || currentUser.name;
                const dept = meta.department || currentUser.address || 'สาขาวิศวกรรมคอมพิวเตอร์';

                // แก้ไขจุดที่มีปัญหาคลาส flex ตีกัน
                guestDiv.classList.remove('flex');
                guestDiv.classList.add('hidden');
                
                userDiv.classList.remove('hidden');
                userDiv.classList.add('flex');

                document.getElementById('navUserAvatar').src = avatarUrl;
                document.getElementById('navUserName').textContent = displayName;
                document.getElementById('myCoursesAvatar').src = avatarUrl;
                document.getElementById('myCoursesUserName').textContent = displayName;
                document.getElementById('myCoursesUserDept').textContent = `${dept} • ${currentUser.email}`;

                const isTutor = currentUser.role === 'tutor';
                if (isTutor) {
                    btnApplyTutor.classList.add('hidden');
                    btnCreateCourse.classList.remove('hidden');
                    navTutorBadge.classList.remove('hidden');
                    if (myCoursesActionBtn) myCoursesActionBtn.textContent = '+ ลงขายคอร์สเรียนใหม่';
                    if (myCoursesRoleBadge) {
                        myCoursesRoleBadge.textContent = `✓ ติวเตอร์ (${meta.real_name ? 'ชื่อจริง: ' + meta.real_name : 'ยืนยันตัวตนแล้ว'})`;
                        myCoursesRoleBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800';
                    }
                    if (mobileBtnTutor) mobileBtnTutor.textContent = '+ ลงขายคอร์ส';
                } else {
                    btnApplyTutor.classList.remove('hidden');
                    btnCreateCourse.classList.add('hidden');
                    navTutorBadge.classList.add('hidden');
                    if (myCoursesActionBtn) myCoursesActionBtn.textContent = '🎓 สมัครเป็นติวเตอร์';
                    if (myCoursesRoleBadge) {
                        myCoursesRoleBadge.textContent = 'สถานะ: นักศึกษาทั่วไป';
                        myCoursesRoleBadge.className = 'px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700';
                    }
                    if (mobileBtnTutor) mobileBtnTutor.textContent = 'สมัครเป็นติวเตอร์';
                }
            } else {
                // คืนค่าคลาสเริ่มต้นเมื่อยังไม่ได้ล็อกอิน
                guestDiv.classList.remove('hidden');
                guestDiv.classList.add('flex');
                
                userDiv.classList.remove('flex');
                userDiv.classList.add('hidden');
                
                btnApplyTutor.classList.remove('hidden');
                btnCreateCourse.classList.add('hidden');
            }
        }

        async function logout(notify = true) {
            try {
                await fetch('/api/logout', { 
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include' 
                });
            } catch (err) {}

            currentUser = null;
            purchasedCourses = [];
            purchasedCourseIds.clear();
            
            // สั่งล้าง localStorage ทิ้งทั้งหมด
            localStorage.clear();
            
            updateAuthUI();
            if (notify) showToast('ออกจากระบบเรียบร้อยแล้ว');
            
            setTimeout(() => {
                window.location.reload();
            }, 800);
        }

        async function addToCart(productId) {
            if (!currentUser) {
                showToast('กรุณาเข้าสู่ระบบก่อนสั่งซื้อคอร์สเรียน', true);
                openAuthModal('login');
                return;
            }

            if (isCoursePurchased(productId)) {
                showToast('คุณได้ซื้อคอร์สเรียนวิชานี้ไปแล้ว สามารถเข้าเรียนได้ทันที', true);
                openVideoPlayerModal(productId);
                return;
            }

            try {
                const res = await fetch('/api/cart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ product_id: productId, quantity: 1 }),
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถเพิ่มคอร์สได้', true);
                    return;
                }
                showToast('เพิ่มคอร์สเรียนลงในตะกร้าแล้ว');
                await fetchCart();
                openCartDrawer();
            } catch (err) {
                showToast('เกิดข้อผิดพลาดในการเชื่อมต่อตะกร้า', true);
            }
        }

        async function fetchCart() {
            if (!currentUser) return;
            try {
                const res = await fetch('/api/cart', {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include'
                });
                const data = await res.json();
                renderCart(data);
            } catch (err) {
                console.error(err);
            }
        }

        async function removeFromCart(itemId) {
            if (!currentUser) return;
            try {
                const res = await fetch(`/api/cart/${itemId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถลบคอร์สออกจากตะกร้าได้', true);
                    return;
                }
                showToast('🗑️ ลบคอร์สเรียนออกจากตะกร้าเรียบร้อยแล้ว');
                await fetchCart();
            } catch (err) {
                showToast('เกิดข้อผิดพลาดในการลบคอร์สเรียน', true);
            }
        }

        function renderCart(data) {
            const cart = data ? data.cart : null;
            const container = document.getElementById('cartItemsContainer');
            const badge = document.getElementById('cartBadge');
            const totalEl = document.getElementById('cartTotalAmount');
            const origRow = document.getElementById('originalTotalRow');
            const origEl = document.getElementById('cartOriginalTotalAmount');
            const discountRow = document.getElementById('discountRow');
            const discountEl = document.getElementById('cartDiscountAmount');
            const discountLabel = document.getElementById('discountLabelText');
            const badgesBox = document.getElementById('appliedCouponsBadges');
            const couponInput = document.getElementById('couponInput');
            const couponBtn = document.getElementById('couponSubmitBtn');

            if (couponInput) couponInput.disabled = false;
            if (couponBtn) {
                couponBtn.disabled = false;
                couponBtn.textContent = 'ใช้โค้ด';
            }

            if (!cart || !cart.items || cart.items.length === 0) {
                badge.textContent = '0';
                totalEl.textContent = '0 บาท';
                if (origRow) origRow.classList.add('hidden');
                discountRow.classList.add('hidden');
                discountRow.classList.remove('flex');
                if (badgesBox) {
                    badgesBox.classList.add('hidden');
                    badgesBox.innerHTML = '';
                }
                container.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-center p-6 text-slate-400 space-y-2">
                        <p class="font-semibold text-slate-600">ยังไม่มีคอร์สเรียนในตะกร้า</p>
                        <p class="text-xs">เลือกคอร์สเรียนที่ต้องการแล้วกดปุ่ม "สั่งซื้อ"</p>
                    </div>
                `;
                return;
            }

            badge.textContent = cart.items.length;
            totalEl.textContent = `${Number(cart.total_amount).toLocaleString()} บาท`;

            const appliedCoupons = data.applied_coupons || [];
            const discountApplied = Number(data.discount_applied || 0);
            const originalTotal = Number(data.original_total || cart.total_amount);

            if (discountApplied > 0 && appliedCoupons.length > 0) {
                if (origRow) {
                    origRow.classList.remove('hidden');
                    origRow.classList.add('flex');
                    origEl.textContent = `${originalTotal.toLocaleString()} บาท`;
                }
                discountRow.classList.remove('hidden');
                discountRow.classList.add('flex');
                discountLabel.textContent = `ส่วนลดคูปอง (${appliedCoupons.join(', ')})`;
                discountEl.textContent = `-${discountApplied.toLocaleString()} บาท`;

                if (badgesBox) {
                    badgesBox.classList.remove('hidden');
                    badgesBox.classList.add('flex');
                    badgesBox.innerHTML = `<span class="text-xs text-slate-500">โค้ดที่ใช้แล้ว:</span>` + appliedCoupons.map(c => `
                        <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold text-xs">✓ ${c}</span>
                    `).join('');
                }
            } else {
                if (origRow) origRow.classList.add('hidden');
                discountRow.classList.add('hidden');
                discountRow.classList.remove('flex');
                if (badgesBox) {
                    badgesBox.classList.add('hidden');
                    badgesBox.innerHTML = '';
                }
            }

            container.innerHTML = cart.items.map(item => {
                const product = item.product || {};
                const courseName = product.name || `คอร์สเรียน #${item.product_id}`;
                const sku = product.sku ? `[${product.sku}] ` : '';
                const imgUrl = (product.images && product.images.length > 0)
                    ? product.images[0].image_url
                    : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=400&q=80';
                const meta = parseCourseMeta(product);

                return `
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3.5 hover:border-slate-300 transition">
                        <img src="${imgUrl}" alt="${courseName}" class="w-20 h-20 rounded-xl object-cover shrink-0 border border-slate-200 bg-slate-900">
                        <div class="flex-1 min-w-0 space-y-1">
                            <h4 class="font-bold text-xs sm:text-sm text-slate-900 line-clamp-2 leading-snug">${sku}${courseName}</h4>
                            <p class="text-xs text-academic-blue font-semibold truncate">👨‍🏫 ผู้สอน: ${meta.tutorName}</p>
                            <div class="pt-1 flex items-center justify-between gap-2">
                                <span class="font-bold text-academic-blue text-sm">
                                    ${Number(item.price).toLocaleString()} บาท
                                </span>
                                <button
                                    onclick="removeFromCart(${item.id})"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs transition cursor-pointer"
                                    title="ลบคอร์สนี้ออกจากตะกร้า"
                                >
                                    <span>🗑️ ลบออก</span>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function openCartDrawer() {
            if (!currentUser) {
                showToast('กรุณาเข้าสู่ระบบเพื่อดูตะกร้าคอร์สเรียน', true);
                openAuthModal('login');
                return;
            }
            fetchCart();
            const drawer = document.getElementById('cartDrawer');
            drawer.classList.remove('hidden');
            drawer.classList.add('flex');
        }

        function closeCartDrawer() {
            const drawer = document.getElementById('cartDrawer');
            drawer.classList.add('hidden');
            drawer.classList.remove('flex');
        }

        async function handleApplyCoupon(e) {
            e.preventDefault();
            const couponInput = document.getElementById('couponInput');
            if (couponInput.disabled) return;
            const code = couponInput.value.trim();
            if (!code) return;

            try {
                const res = await fetch('/api/apply-coupon', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ code }),
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถใช้โค้ดส่วนลดได้', true);
                    return;
                }
                couponInput.value = '';
                showToast(data.message || 'ใช้โค้ดส่วนลดสำเร็จ');
                await fetchCart();
            } catch (err) {
                showToast('ไม่สามารถใช้คูปองได้', true);
            }
        }

        async function handleCheckout() {
            try {
                const res = await fetch('/api/checkout', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    credentials: 'include'
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถทำรายการได้', true);
                    return;
                }
                showToast('ชำระเงินสำเร็จ! คอร์สเรียนถูกเพิ่มในเมนู "คอร์สของฉัน" แล้ว');
                await fetchMyCoursesData();
                await fetchCart();
                closeCartDrawer();
                switchMainView('my-courses');
                switchMyCoursesSubTab('purchased');
            } catch (err) {
                showToast('เกิดข้อผิดพลาดในการชำระเงิน', true);
            }
        }

        async function submitReview(e) {
            e.preventDefault();
            if (!currentUser) {
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
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        product_id: currentDetailCourse.id,
                        rating,
                        comment
                    }),
                    credentials: 'include'
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
    </script>
</body>
</html>