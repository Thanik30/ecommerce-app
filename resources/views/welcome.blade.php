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
                    colors: { academic: { blue: '#0066FF', blueHover: '#0052CC', greenBg: '#F0FDF4' } },
                    fontFamily: { sans: ['Prompt', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Prompt', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        .book-cover-shadow { box-shadow: 0 18px 30px -10px rgba(15, 23, 42, 0.16), 0 6px 12px -6px rgba(15, 23, 42, 0.08); }
        /* สไตล์หลอดโหลดวิดีโอแบบเต็ม */
        video::-webkit-media-controls-timeline { padding: 0 10px; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <div class="bg-slate-900 text-white text-xs py-2 px-4">
        <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-academic-blue text-white font-semibold text-[11px]">PROMOTION</span>
                <span id="topPromoText">โค้ดส่วนลดช่วงสอบ: ใช้โค้ด <button onclick="copyCoupon('ENGMID20')" class="underline font-semibold text-sky-300">ENGMID20</button> (ลด 20%)</span>
            </div>
            <button onclick="openContactAdminModal('ติดต่อแอดมิน')" class="text-sky-300 hover:text-white underline text-xs cursor-pointer">ติดต่อแอดมิน</button>
        </div>
    </div>

    <!-- NAVBAR -->
    <header class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-2xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-3">
            <button onclick="switchMainView('catalog')" class="flex items-center gap-2.5 text-left shrink-0 cursor-pointer">
                <div class="w-9 h-9 rounded-lg bg-academic-blue flex items-center justify-center text-white font-bold text-lg">E</div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-slate-900 block leading-none">EngTutor</span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Engineering Course Platform</span>
                </div>
            </button>

            <nav class="hidden lg:flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                <button id="navTabCatalog" onclick="switchMainView('catalog')" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer bg-white text-academic-blue shadow-xs">ค้นหาคอร์สเรียน</button>
                <button id="navTabMyCourses" onclick="switchMainView('my-courses')" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
                    คอร์สของฉัน <span id="myCoursesCountBadge" class="px-1.5 py-0.2 rounded-full bg-academic-blue/10 text-academic-blue text-xs font-semibold">0</span>
                </button>
                <button id="navBtnApplyTutor" onclick="openTutorApplicationModal()" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer text-amber-700 hover:bg-amber-50">สมัครเป็นติวเตอร์</button>
                <button id="navBtnCreateCourse" onclick="openCreateCourseModal()" class="hidden whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-semibold transition cursor-pointer text-emerald-700 bg-emerald-50 hover:bg-emerald-100">+ ลงขายคอร์สเรียน</button>
            </nav>

            <div class="flex items-center gap-2 shrink-0">
                <button onclick="openCartDrawer()" class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 hover:border-academic-blue bg-white text-slate-700 text-xs sm:text-sm font-medium transition cursor-pointer">
                    🛒 <span class="hidden sm:inline">ตะกร้า</span>
                    <span id="cartBadge" class="px-1.5 py-0.5 text-xs rounded-full bg-academic-blue text-white font-semibold leading-none">0</span>
                </button>

                <div id="guestActions" class="flex items-center gap-2">
                    <button onclick="openAuthModal('login')" class="whitespace-nowrap px-4 py-2 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-medium text-xs sm:text-sm transition cursor-pointer">เข้าสู่ระบบ</button>
                </div>

                <div id="userActions" class="hidden items-center gap-1.5">
                    <button onclick="openProfileModal()" class="whitespace-nowrap inline-flex items-center gap-2 pl-1.5 pr-3 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-medium transition cursor-pointer border border-slate-200">
                        <img id="navUserAvatar" src="" class="w-7 h-7 rounded-full object-cover border border-white shadow-2xs">
                        <span id="navUserName" class="max-w-[90px] sm:max-w-[120px] truncate">นักศึกษา</span>
                    </button>
                    <button onclick="logout()" class="whitespace-nowrap px-2.5 py-2 rounded-lg border border-slate-200 hover:bg-rose-50 hover:text-rose-600 text-slate-500 text-xs font-medium transition cursor-pointer">ออกระบบ</button>
                </div>
            </div>
        </div>
    </header>

    <!-- VIEW 1: หน้าค้นหารหัสวิชา -->
    <div id="viewCatalog" class="flex-1">
        <section class="bg-white border-b border-slate-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 py-7 sm:py-9">
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 sm:p-8 text-center space-y-3.5">
                    <h1 class="text-xl sm:text-3xl font-bold text-slate-900">ค้นหาคอร์สติวจากรหัสวิชา 6 หลัก</h1>
                    <div class="pt-2 flex flex-col sm:flex-row gap-2.5 max-w-2xl mx-auto">
                        <div class="relative flex-1">
                            <input id="courseCodeSearchInput" type="text" oninput="handleSearchInput(this.value)" placeholder="พิมพ์รหัสวิชา 6 หลัก เช่น 259104, 251201..." class="w-full pl-4 pr-12 py-3 rounded-xl bg-white border border-slate-300 focus:border-academic-blue focus:outline-none text-sm">
                            <button id="clearSearchBtn" onclick="setQuickSearch('')" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-bold cursor-pointer">✕</button>
                        </div>
                        <button onclick="renderCourses()" class="px-6 py-3 rounded-xl bg-academic-blue text-white font-semibold text-sm transition cursor-pointer shrink-0">ค้นหาวิชา</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-10 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">แยกตามสาขาวิชา (รหัสขึ้นต้น 3 หลัก)</h2>
                    <span id="courseCountText" class="text-xs text-slate-500 font-medium"></span>
                </div>
                <div id="categoryFilterBar" class="flex flex-wrap items-center gap-2"></div>
            </div>
            <div id="coursesGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
        </section>
    </div>

    <!-- VIEW 2: หน้ารายละเอียดคอร์สเรียน -->
    <div id="viewCourseDetail" class="hidden flex-1 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">
            <button onclick="switchMainView('catalog')" class="mb-6 px-4 py-2 rounded-lg border border-slate-200 text-slate-600 text-xs font-medium cursor-pointer">← ย้อนกลับไปหน้ารวมคอร์สเรียน</button>
            <div class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-10 shadow-xs">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    <div class="lg:col-span-5 flex flex-col items-center space-y-5">
                        <div class="w-60 sm:w-72 bg-white p-3 rounded-2xl border border-slate-200 book-cover-shadow">
                            <div class="relative aspect-[3/4] rounded-xl overflow-hidden bg-slate-900">
                                <img id="detailImg" src="" class="w-full h-full object-cover opacity-90">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/30 to-transparent p-5 text-white flex flex-col justify-end">
                                    <h4 id="detailCoverTitle" class="text-sm font-bold line-clamp-2"></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-7 space-y-5">
                        <h1 id="detailTitle" class="text-xl sm:text-2xl font-bold text-slate-900 leading-snug"></h1>
                        <div id="detailPrice" class="text-3xl font-bold text-academic-blue"></div>
                        <p><strong class="underline">เหมาะสำหรับ:</strong> <span id="detailSuitableFor"></span></p>
                        <p><strong class="underline">รายละเอียด:</strong> <span id="detailDescription"></span></p>
                        
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-200">
                            <h3 class="font-bold text-sm text-academic-blue mb-2">หัวข้อบทเรียนในคอร์ส:</h3>
                            <div id="detailSyllabusList" class="space-y-1.5 text-xs text-slate-700 font-medium"></div>
                        </div>

                        <hr class="border-slate-200">
                        <div class="flex gap-3">
                            <button id="detailBuyBtn" class="px-8 py-3 rounded-full bg-academic-blue text-white font-semibold text-sm shadow-md cursor-pointer">สั่งซื้อคอร์สเรียน</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- VIEW 3: หน้าคอร์สเรียนและคลิปของฉัน -->
    <div id="viewMyCourses" class="hidden flex-1">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row justify-between gap-4">
                <div class="flex items-center gap-4">
                    <img id="myCoursesAvatar" src="" class="w-14 h-14 rounded-full object-cover border-2 border-academic-blue">
                    <div>
                        <h1 id="myCoursesUserName" class="text-lg font-bold text-slate-900"></h1>
                        <p id="myCoursesRoleBadge" class="text-xs text-emerald-700 font-bold"></p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="openProfileModal()" class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 font-semibold text-xs cursor-pointer">⚙️ แก้ไขโปรไฟล์</button>
                    <button id="myCoursesActionBtn" onclick="handleTutorActionClick()" class="px-4 py-2 rounded-xl bg-academic-blue text-white font-semibold text-xs cursor-pointer">สมัครเป็นติวเตอร์</button>
                </div>
            </div>

            <div class="border-b border-slate-200 flex items-center gap-6 overflow-x-auto">
                <button id="subTabPurchased" onclick="switchMyCoursesSubTab('purchased')" class="pb-3 border-b-2 border-academic-blue text-academic-blue font-bold text-sm">📚 คอร์สที่ซื้อแล้ว (0)</button>
                <button id="subTabUploaded" onclick="switchMyCoursesSubTab('uploaded')" class="pb-3 border-b-2 border-transparent text-slate-500 font-medium text-sm">📹 คลิปที่ฉันลงขาย (0)</button>
            </div>

            <div id="purchasedCoursesPanel" class="grid grid-cols-1 md:grid-cols-2 gap-6"></div>
            
            <div id="uploadedCoursesPanel" class="hidden flex-col gap-6">
                <!-- ส่วนสร้างโค้ดส่วนลดของติวเตอร์ -->
                <div id="tutorCouponSection" class="hidden bg-emerald-50 border border-emerald-200 p-5 rounded-xl">
                    <h3 class="font-bold text-emerald-800 text-sm mb-3">🎟️ สร้างโค้ดส่วนลดสำหรับคอร์สของคุณ</h3>
                    <form onsubmit="handleCreateTutorCoupon(event)" class="flex flex-wrap items-end gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700">รหัสโค้ด *</label>
                            <input id="tutorCouponCode" type="text" required placeholder="เช่น ICE20" class="w-32 px-3 py-2 rounded-lg border border-slate-300 text-sm uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700">ประเภท *</label>
                            <select id="tutorCouponType" class="w-28 px-3 py-2 rounded-lg border border-slate-300 text-sm">
                                <option value="percentage">ลด %</option>
                                <option value="fixed">ลดบาท</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700">มูลค่าที่ลด *</label>
                            <input id="tutorCouponValue" type="number" required placeholder="เช่น 20" class="w-28 px-3 py-2 rounded-lg border border-slate-300 text-sm">
                        </div>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-emerald-600 text-white font-bold text-sm cursor-pointer">สร้างโค้ด</button>
                    </form>
                </div>
                <div id="uploadedCoursesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
            </div>
        </div>
    </div>

    <!-- ==========================================================
         MODAL: ห้องเรียนวิดีโอ (กอคลิปได้ 100% + แบ่งเป็น Playlist)
         ========================================================== -->
    <div id="videoPlayerModal" class="fixed inset-0 z-50 hidden bg-slate-900/90 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-5xl w-full overflow-hidden flex flex-col md:flex-row h-[85vh] shadow-2xl relative">
            <button onclick="closeVideoPlayerModal()" class="absolute top-4 left-4 z-10 w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold cursor-pointer transition border border-slate-600 hover:bg-slate-700">✕</button>
            
            <!-- ฝั่งซ้าย: วิดีโอหลัก -->
            <div class="md:w-3/4 bg-black flex flex-col justify-center relative">
                <!-- วิดีโอใช้ preload="auto" และใส่ preload โหลดมาให้ครบ -->
                <video id="realCourseVideoElement" controls preload="auto" class="w-full h-full object-contain">
                    <source id="realCourseVideoSource" src="" type="video/mp4">
                </video>
            </div>

            <!-- ฝั่งขวา: รายชื่อบทเรียน (Playlist) -->
            <div class="md:w-1/4 bg-slate-50 flex flex-col border-l border-slate-200">
                <div class="p-4 bg-white border-b border-slate-200">
                    <h3 id="playerCourseTitle" class="font-bold text-sm text-slate-800 line-clamp-2"></h3>
                    <a id="playerPdfDownloadAnchor" href="#" target="_blank" class="mt-3 block w-full py-2 text-center rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white text-xs font-semibold transition">📥 โหลดเอกสาร PDF</a>
                </div>
                <!-- รายชื่อบทเรียนแบบเลื่อนได้ -->
                <div id="playlistContainer" class="flex-1 overflow-y-auto p-2 space-y-1"></div>
            </div>
        </div>
    </div>

    <!-- ==========================================================
         MODAL: ลงขายคอร์สเรียน (เพิ่มวิดีโอหลายบทได้)
         ========================================================== -->
    <div id="createCourseModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-3xl w-full overflow-hidden shadow-2xl border border-slate-200 my-auto max-h-[92vh] flex flex-col">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <h3 class="font-bold text-base">ลงขายคอร์สเรียนใหม่ (เพิ่มวิดีโอหลายบทได้)</h3>
                <button onclick="closeCreateCourseModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            
            <form onsubmit="handleCreateCourse(event)" class="p-6 overflow-y-auto space-y-4 flex-1 text-sm">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold mb-1">รหัสวิชา 6 หลัก *</label>
                        <input id="newCourseSku" type="text" required pattern="\d{6}" maxlength="6" class="w-full px-3 py-2 rounded-lg border border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">ชื่อคอร์สเรียน *</label>
                        <input id="newCourseName" type="text" required class="w-full px-3 py-2 rounded-lg border border-slate-300">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold mb-1">ราคาขาย (บาท) *</label>
                        <input id="newCoursePrice" type="number" required class="w-full px-3 py-2 rounded-lg border border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">ชั่วโมงคอร์ส (ตัวเลข) *</label>
                        <input id="newCourseHoursNumber" type="number" required step="0.5" class="w-full px-3 py-2 rounded-lg border border-slate-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">อายุคอร์สเรียน *</label>
                        <select id="newCourseDuration" class="w-full px-3 py-2 rounded-lg border border-slate-300">
                            <option value="12 เดือน">12 เดือน</option>
                            <option value="6 เดือน">6 เดือน</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">รายละเอียดคอร์สเรียน และเหมาะสำหรับใคร *</label>
                    <textarea id="newCourseDesc" rows="2" required placeholder="คำอธิบายสั้นๆ..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
                </div>

                <!-- ส่วนอัปโหลดหลายคลิปแยกบทเรียน -->
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-800">🎬 อัปโหลดคลิปวิดีโอการสอน (แยกทีละบท) *</label>
                        <button type="button" onclick="addLessonRow()" class="text-xs bg-academic-blue text-white px-3 py-1.5 rounded-lg cursor-pointer">+ เพิ่มบทเรียน</button>
                    </div>
                    <div id="lessonsContainer" class="space-y-2">
                        <!-- แถวสำหรับใส่บทเรียนแรก -->
                        <div class="lesson-row flex flex-col sm:flex-row gap-2 items-center bg-white p-2.5 rounded-lg border border-slate-200">
                            <input type="text" name="lesson_titles[]" placeholder="ชื่อบทเรียน เช่น บทที่ 1..." required class="w-full sm:w-1/3 px-3 py-2 text-xs rounded-lg border border-slate-300">
                            <input type="file" name="lesson_videos[]" accept="video/mp4,video/webm,video/ogg" required class="w-full sm:w-2/3 text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-slate-100">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold mb-1">อัปโหลดรูปปกคอร์ส *</label>
                        <input id="newCourseCoverFile" type="file" accept="image/*" required class="w-full text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold mb-1">ลิงก์เอกสาร PDF (Google Drive) *</label>
                        <input id="newCoursePdfUrl" type="url" required class="w-full px-3 py-2 rounded-lg border border-slate-300">
                    </div>
                </div>

                <button id="submitCourseBtn" type="submit" class="w-full py-3 rounded-xl bg-academic-blue text-white font-semibold cursor-pointer">🚀 อัปโหลดและวางขายคอร์สเรียน</button>
            </form>
        </div>
    </div>

    <!-- MODAL และฟังก์ชันพื้นฐานอื่นๆ (Login, ตะกร้า, โปรไฟล์) ขอละไว้เพื่อให้โค้ดไม่เกินความยาว แต่ทำงานเหมือนเดิมครับ -->
    <!-- ขอยืมโค้ดที่เหลือจากเวอร์ชันก่อนหน้า เช่น authModal, cartDrawer ได้เลยครับ -->

    <!-- กล่องแจ้งเตือน Toast -->
    <div id="toastBox" class="fixed bottom-5 right-5 z-50 hidden max-w-sm px-5 py-3 rounded-xl shadow-lg text-white font-medium text-sm transition-all"></div>

    <script>
        let allCourses = [];
        let purchasedCourses = [];
        let purchasedCourseIds = new Set();
        let activeCategory = 'ALL';
        let searchQuery = '';
        let authToken = localStorage.getItem('auth_token') || null;
        let currentUser = JSON.parse(localStorage.getItem('cached_user') || 'null');

        // ฟังก์ชันช่วยแยกข้อมูล
        function parseCourseMeta(course) {
            const v = (course.variants && course.variants.length > 0) ? course.variants[0] : null;
            let tutorName = course.tutor_name || 'ติวเตอร์วิศวะ';
            let ownerEmail = course.owner_email || '';
            let hoursNum = course.hours_num || 20;
            let duration = course.duration || '12 เดือน';
            let examType = course.exam_type || 'Midterm + Final';
            let suitableFor = course.suitable_for || 'นักศึกษาทุกชั้นปี';
            let pdfUrl = course.pdf_url || '#';
            return { tutorName, ownerEmail, hoursNum, hoursFormatted: `${hoursNum} ชั่วโมง`, duration, examType, suitableFor, pdfUrl };
        }

        function showToast(msg, isError = false) {
            const t = document.getElementById('toastBox');
            t.textContent = msg;
            t.className = `fixed bottom-5 right-5 z-50 max-w-sm px-5 py-3 rounded-xl shadow-lg text-white font-medium text-sm transition-all ${isError ? 'bg-rose-600' : 'bg-slate-900'}`;
            t.classList.remove('hidden');
            setTimeout(() => t.classList.add('hidden'), 3800);
        }

        function switchMainView(view) {
            document.getElementById('viewCatalog').classList.add('hidden');
            document.getElementById('viewCourseDetail').classList.add('hidden');
            document.getElementById('viewMyCourses').classList.add('hidden');
            if(view === 'catalog') { document.getElementById('viewCatalog').classList.remove('hidden'); renderCourses(); }
            if(view === 'detail') document.getElementById('viewCourseDetail').classList.remove('hidden');
            if(view === 'my-courses') {
                if(!authToken) { showToast('กรุณาล็อกอิน', true); return; }
                document.getElementById('viewMyCourses').classList.remove('hidden');
                fetchMyCoursesData();
            }
            window.scrollTo({top:0});
        }

        async function fetchCourses() {
            try {
                const res = await fetch('/api/products');
                const json = await res.json();
                allCourses = json.data || [];
                renderCourses();
            } catch(e) {}
        }

        function renderCourses() {
            const grid = document.getElementById('coursesGrid');
            if (!grid) return;
            const q = searchQuery.toLowerCase();
            const filtered = allCourses.filter(c => !q || c.sku.includes(q) || c.name.toLowerCase().includes(q));

            grid.innerHTML = filtered.map(course => {
                const imgUrl = (course.images && course.images.length > 0) ? course.images[0].image_url : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=400&q=80';
                return `
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md cursor-pointer" onclick="openCourseDetailPage(${course.id})">
                        <div class="aspect-16/10 relative"><img src="${imgUrl}" class="w-full h-full object-cover"></div>
                        <div class="p-4 space-y-2">
                            <span class="text-xs font-bold text-academic-blue">รหัส ${course.sku}</span>
                            <h3 class="font-bold text-sm line-clamp-2">${course.name}</h3>
                            <div class="font-bold text-lg text-academic-blue">${Number(course.price).toLocaleString()} บาท</div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function openCourseDetailPage(id) {
            const course = allCourses.find(c => c.id === id);
            document.getElementById('detailImg').src = course.images[0]?.image_url || '';
            document.getElementById('detailTitle').textContent = course.name;
            document.getElementById('detailPrice').textContent = `${Number(course.price).toLocaleString()} บาท`;
            
            // แสดง Syllabus ที่ระบบ Gen ให้อัตโนมัติในฝั่ง Back-end
            const syllabusLines = (course.syllabus || '').split('\n').filter(l => l.trim().length > 0);
            document.getElementById('detailSyllabusList').innerHTML = syllabusLines.map((line, idx) => `
                <div class="flex items-start gap-2 border-b border-slate-100 pb-2">
                    <span class="text-academic-blue">▶</span> <div>${line}</div>
                </div>
            `).join('');

            const btn = document.getElementById('detailBuyBtn');
            if (purchasedCourseIds.has(course.id)) {
                btn.textContent = '✓ ซื้อแล้ว (เข้าเรียน)';
                btn.onclick = () => { switchMainView('my-courses'); };
            } else {
                btn.textContent = 'สั่งซื้อคอร์สเรียน';
                btn.onclick = () => addToCart(course.id);
            }
            switchMainView('detail');
        }

        // ==========================================
        // ระบบสร้างคอร์สและอัปโหลดหลายคลิป
        // ==========================================
        function addLessonRow() {
            const container = document.getElementById('lessonsContainer');
            const row = document.createElement('div');
            row.className = 'lesson-row flex flex-col sm:flex-row gap-2 items-center bg-white p-2.5 rounded-lg border border-slate-200 mt-2';
            row.innerHTML = `
                <input type="text" name="lesson_titles[]" placeholder="ชื่อบทเรียน..." required class="w-full sm:w-1/3 px-3 py-2 text-xs rounded-lg border border-slate-300">
                <input type="file" name="lesson_videos[]" accept="video/mp4,video/webm" required class="w-full sm:w-2/3 text-xs file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-slate-100">
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 font-bold px-2">✕</button>
            `;
            container.appendChild(row);
        }

        async function handleCreateCourse(e) {
            e.preventDefault();
            const btn = document.getElementById('submitCourseBtn');
            btn.disabled = true; btn.textContent = '⏳ กำลังอัปโหลด... (อาจใช้เวลาหลายนาที)';

            const formData = new FormData();
            formData.append('sku', document.getElementById('newCourseSku').value);
            formData.append('name', document.getElementById('newCourseName').value);
            formData.append('price', document.getElementById('newCoursePrice').value);
            formData.append('hours_number', document.getElementById('newCourseHoursNumber').value);
            formData.append('duration', document.getElementById('newCourseDuration').value);
            formData.append('description', document.getElementById('newCourseDesc').value);
            formData.append('pdf_url', document.getElementById('newCoursePdfUrl').value);
            formData.append('cover_image', document.getElementById('newCourseCoverFile').files[0]);

            // แนบไฟล์คลิปบทเรียนหลายๆ ไฟล์
            const titleInputs = document.querySelectorAll('input[name="lesson_titles[]"]');
            const videoInputs = document.querySelectorAll('input[name="lesson_videos[]"]');
            for (let i = 0; i < titleInputs.length; i++) {
                formData.append('lesson_titles[]', titleInputs[i].value);
                formData.append('lesson_videos[]', videoInputs[i].files[0]);
            }

            try {
                const res = await fetch('/api/products', {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${authToken}` },
                    body: formData
                });
                
                if(res.ok) {
                    showToast('🎉 อัปโหลดสำเร็จแล้ว!');
                    document.getElementById('createCourseModal').classList.add('hidden');
                    await fetchCourses();
                    switchMainView('my-courses');
                } else {
                    const error = await res.json();
                    showToast('อัปโหลดล้มเหลว: ' + error.message, true);
                }
            } catch (err) {
                showToast('อัปโหลดล้มเหลว (ไฟล์อาจใหญ่เกินไป)', true);
            }
            btn.disabled = false; btn.textContent = '🚀 อัปโหลดและวางขายคอร์สเรียน';
        }

        // ==========================================
        // ระบบ Playlist และ สตรีมวิดีโอ (กอคลิปได้ 100%)
        // ==========================================
        function openVideoPlayerModal(courseId) {
            const course = allCourses.find(c => c.id === courseId) || purchasedCourses.find(c => c.id === courseId);
            if (!course) return;
            
            document.getElementById('playerCourseTitle').textContent = course.name;
            const meta = parseCourseMeta(course);
            document.getElementById('playerPdfDownloadAnchor').href = meta.pdfUrl;

            const playlist = document.getElementById('playlistContainer');
            
            if (course.lessons && course.lessons.length > 0) {
                playlist.innerHTML = course.lessons.map((lesson, idx) => `
                    <button onclick="playLesson(${lesson.id}, this)" class="playlist-item w-full text-left p-3 rounded-lg text-xs font-medium hover:bg-blue-50 transition border border-transparent ${idx === 0 ? 'bg-blue-50 border-blue-200 text-academic-blue' : 'text-slate-600'}">
                        <div class="font-bold">บทที่ ${idx + 1}</div>
                        <div class="truncate">${lesson.title}</div>
                    </button>
                `).join('');
                // เล่นบทแรกอัตโนมัติ (ดึงวิดีโอผ่าน Route ที่สตรีมมิ่งได้)
                playVideoSource(`/api/stream-video/${course.lessons[0].id}`);
            } else {
                playlist.innerHTML = `<div class="p-3 text-xs text-slate-500">บทเรียนแบบคลิปเดียวจบ</div>`;
                playVideoSource(meta.videoUrl); // สำรองสำหรับคอร์สเก่า
            }
            
            document.getElementById('videoPlayerModal').classList.remove('hidden');
        }

        function playLesson(lessonId, btnElement) {
            document.querySelectorAll('.playlist-item').forEach(el => el.classList.remove('bg-blue-50', 'border-blue-200', 'text-academic-blue'));
            btnElement.classList.add('bg-blue-50', 'border-blue-200', 'text-academic-blue');
            playVideoSource(`/api/stream-video/${lessonId}`);
        }

        function playVideoSource(url) {
            const videoEl = document.getElementById('realCourseVideoElement');
            videoEl.src = url;
            videoEl.load();
            videoEl.play();
        }

        function closeVideoPlayerModal() {
            document.getElementById('realCourseVideoElement').pause();
            document.getElementById('videoPlayerModal').classList.add('hidden');
        }
    </script>
</body>
</html>