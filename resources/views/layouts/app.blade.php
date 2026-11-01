<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EngTutor Academy — ระบบคอร์สเรียนคณะวิศวกรรมศาสตร์')</title>
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
        body { font-family: 'Prompt', sans-serif; background-color: #F8FAFC; color: #1E293B; }
        .text-body-thai { font-family: 'Prompt', sans-serif; line-height: 1.85; }
        .book-cover-shadow { box-shadow: 0 18px 30px -10px rgba(15, 23, 42, 0.16), 0 6px 12px -6px rgba(15, 23, 42, 0.08); }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- แถบแจ้งโค้ดส่วนลดด้านบนสุด -->
    <div class="bg-slate-900 text-white text-xs py-2 px-4">
        <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 rounded bg-academic-blue text-white font-semibold text-[11px]">PROMOTION</span>
                <span>
                    <span data-i18n="promo_prefix">โค้ดส่วนลดช่วงสอบ: คลิกคัดลอก</span>
                    <button onclick="copyCoupon('ENGMID20')" class="underline font-semibold text-sky-300 hover:text-white cursor-pointer">ENGMID20</button> (-20%) /
                    <button onclick="copyCoupon('PASSA100')" class="underline font-semibold text-sky-300 hover:text-white cursor-pointer">PASSA100</button> (-100฿)
                </span>
            </div>
            <button onclick="openContactAdminModal('ติดต่อแอดมิน')" class="text-sky-300 hover:text-white underline text-xs cursor-pointer" data-i18n="nav_contact_admin">
                ติดต่อแอดมิน
            </button>
        </div>
    </div>

    <!-- NAVBAR หลัก -->
    <header class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-2xs">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-3">
            <a href="/" class="flex items-center gap-2.5 text-left shrink-0">
                <div class="w-9 h-9 rounded-lg bg-academic-blue flex items-center justify-center text-white font-bold text-lg">E</div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-slate-900 block leading-none">EngTutor</span>
                    <span class="text-[11px] text-slate-500 block mt-0.5">Engineering Course Platform</span>
                </div>
            </a>

            <nav class="hidden lg:flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                <a href="/" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition {{ request()->is('/') || request()->is('courses/*') ? 'bg-white text-academic-blue shadow-xs' : 'text-slate-600 hover:text-slate-900' }}" data-i18n="nav_search_courses">
                    ค้นหาคอร์สเรียน
                </a>
                <a href="/my-courses" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition {{ request()->is('my-courses') ? 'bg-white text-academic-blue shadow-xs' : 'text-slate-600 hover:text-slate-900' }}" data-i18n="nav_my_courses">
                    คอร์สของฉัน
                </a>
                <a id="navBtnApplyTutor" href="/apply-tutor" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition {{ request()->is('apply-tutor') ? 'bg-white text-amber-700 shadow-xs' : 'text-amber-700 hover:bg-amber-50' }}" data-i18n="nav_apply_tutor">
                    สมัครเป็นติวเตอร์
                </a>
                <a id="navBtnCreateCourse" href="/create-course" class="hidden whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-semibold transition {{ request()->is('create-course') ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}" data-i18n="nav_create_course">
                    + ลงขายคอร์สเรียน
                </a>
                <button onclick="openContactAdminModal('ติดต่อแอดมิน')" class="whitespace-nowrap px-3.5 py-1.5 rounded-lg text-sm font-medium transition cursor-pointer text-slate-600 hover:text-slate-900" data-i18n="nav_contact_admin">
                    ติดต่อแอดมิน
                </button>
            </nav>

            <div class="flex items-center gap-2 shrink-0">
                <button onclick="toggleLanguage()" id="langSwitcherBtn" class="px-2.5 py-1.5 rounded-lg border border-slate-200 hover:border-academic-blue bg-slate-50 text-slate-700 text-xs font-bold transition cursor-pointer">
                    🌐 TH
                </button>

                <button onclick="openCartDrawer()" class="whitespace-nowrap inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 hover:border-academic-blue bg-white text-slate-700 text-xs sm:text-sm font-medium transition cursor-pointer">
                    <svg class="w-4 h-4 text-academic-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="hidden sm:inline" data-i18n="nav_cart">ตะกร้า</span>
                    <span id="cartBadge" class="px-1.5 py-0.5 text-xs rounded-full bg-academic-blue text-white font-semibold leading-none">0</span>
                </button>

                <div id="guestActions" class="flex items-center gap-2">
                    <button onclick="openAuthModal('login')" class="whitespace-nowrap px-4 py-2 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-medium text-xs sm:text-sm transition cursor-pointer" data-i18n="nav_login">
                        เข้าสู่ระบบ
                    </button>
                </div>

                <div id="userActions" class="hidden items-center gap-1.5">
                    <a href="/profile" class="whitespace-nowrap inline-flex items-center gap-2 pl-1.5 pr-3 py-1 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs sm:text-sm font-medium transition border border-slate-200" title="แก้ไขโปรไฟล์">
                        <img id="navUserAvatar" src="https://ui-avatars.com/api/?name=U&background=0066FF&color=fff" alt="Avatar" class="w-7 h-7 rounded-full object-cover border border-white shadow-2xs">
                        <span id="navUserName" class="max-w-[95px] sm:max-w-[130px] truncate">นักศึกษา</span>
                        <span id="navTutorBadge" class="hidden px-1.5 py-0.5 rounded bg-emerald-600 text-white text-[10px] font-bold">TUTOR</span>
                    </a>
                    <button onclick="logout()" class="whitespace-nowrap px-2.5 py-2 rounded-lg border border-slate-200 hover:bg-rose-50 hover:text-rose-600 text-slate-500 text-xs font-medium transition cursor-pointer" data-i18n="nav_logout">
                        ออก
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:hidden border-t border-slate-100 px-3 py-2 flex items-center justify-around bg-slate-50 text-xs font-medium overflow-x-auto gap-2">
            <a href="/" class="whitespace-nowrap py-1 text-academic-blue font-semibold" data-i18n="nav_search_courses">ค้นหาคอร์สเรียน</a>
            <a href="/my-courses" class="whitespace-nowrap py-1 text-slate-600" data-i18n="nav_my_courses">คอร์สของฉัน</a>
            <a id="mobileBtnApplyTutor" href="/apply-tutor" class="whitespace-nowrap py-1 text-amber-700 font-semibold" data-i18n="nav_apply_tutor">สมัครเป็นติวเตอร์</a>
            <a id="mobileBtnCreateCourse" href="/create-course" class="hidden whitespace-nowrap py-1 text-emerald-700 font-semibold" data-i18n="nav_create_course">+ ลงขายคอร์ส</a>
            <a href="/profile" class="whitespace-nowrap py-1 text-slate-600" data-i18n="nav_profile">โปรไฟล์</a>
        </div>
    </header>

    <main class="flex-1 flex flex-col">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs sm:text-sm text-slate-500">
            <div class="flex items-center gap-2.5">
                <span class="w-6 h-6 rounded bg-academic-blue text-white font-bold flex items-center justify-center text-xs">E</span>
                <span class="font-bold text-slate-800">EngTutor Academy</span>
                <span data-i18n="footer_subtitle">— แพลตฟอร์มคอร์สเรียนและติวเตอร์คณะวิศวกรรมศาสตร์</span>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="openContactAdminModal('ติดต่อแอดมิน')" class="text-academic-blue hover:underline font-medium cursor-pointer" data-i18n="nav_contact_admin">ติดต่อแอดมิน</button>
                <span>Thank you for using</span>
            </div>
        </div>
    </footer>

    <!-- DRAWER: ตะกร้าคอร์สเรียน -->
    <div id="cartDrawer" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs justify-end">
        <div class="bg-white w-full max-w-md h-full shadow-2xl flex flex-col justify-between">
            <div class="p-5 bg-slate-900 text-white flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base" data-i18n="nav_cart">ตะกร้าคอร์สเรียน</h3>
                    <p class="text-xs text-slate-300 mt-0.5" data-i18n="cart_subtitle">สามารถกรอกโค้ดส่วนลดเพิ่มได้ (โค้ดแต่ละรหัสใช้ได้ 1 ครั้ง)</p>
                </div>
                <button onclick="closeCartDrawer()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>

            <div id="cartItemsContainer" class="flex-1 p-5 overflow-y-auto space-y-3"></div>

            <div class="p-5 bg-slate-50 border-t border-slate-200 space-y-4">
                <div id="appliedCouponsBadges" class="hidden flex-wrap items-center gap-1.5"></div>

                <form onsubmit="handleApplyCoupon(event)" class="flex gap-2">
                    <input id="couponInput" type="text" placeholder="กรอกโค้ด เช่น ENGMID20 หรือ PASSA100" class="flex-1 px-3.5 py-2 rounded-lg bg-white border border-slate-300 text-sm font-semibold uppercase focus:outline-none focus:border-academic-blue">
                    <button id="couponSubmitBtn" type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs sm:text-sm transition cursor-pointer shrink-0" data-i18n="btn_apply_code">
                        ใช้โค้ด
                    </button>
                </form>

                <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-1.5">
                    <div id="originalTotalRow" class="hidden justify-between text-xs text-slate-500">
                        <span data-i18n="cart_original_total">ราคารวมปกติ</span>
                        <span id="cartOriginalTotalAmount">0 บาท</span>
                    </div>
                    <div id="discountRow" class="hidden justify-between text-xs text-emerald-600 font-semibold">
                        <span id="discountLabelText">ส่วนลดคูปองรวม</span>
                        <span id="cartDiscountAmount">-0 บาท</span>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-sm font-semibold text-slate-600" data-i18n="cart_net_total">ยอดชำระสุทธิ:</span>
                        <span id="cartTotalAmount" class="text-xl font-bold text-academic-blue">0 บาท</span>
                    </div>
                </div>

                <button onclick="handleCheckout()" class="w-full py-3 rounded-xl bg-academic-blue hover:bg-academic-blueHover text-white font-semibold text-sm sm:text-base shadow-sm transition cursor-pointer" data-i18n="btn_checkout">
                    ยืนยันการชำระเงิน
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: ติดต่อแอดมิน -->
    <div id="contactAdminModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200">
            <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
                <h3 class="font-bold text-base" data-i18n="nav_contact_admin">ติดต่อแอดมินผู้ดูแลระบบ</h3>
                <button onclick="closeContactAdminModal()" class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center font-bold cursor-pointer">✕</button>
            </div>
            <form onsubmit="handleSendAdminMessage(event)" class="p-6 space-y-4 text-sm">
                <div class="p-3 rounded-xl bg-slate-100 text-xs text-slate-600 leading-relaxed" data-i18n="admin_modal_desc">
                    สำหรับการแจ้งแก้ไขราคาคอร์ส แก้ไขรายละเอียดคอร์สที่วางขายแล้ว การขอจัดโปรโมชันลดราคา หรือแจ้งปัญหาการใช้งาน (ดำเนินการโดยแอดมินเท่านั้น)
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" data-i18n="label_topic">หัวข้อติดต่อ *</label>
                    <input id="adminTopicInput" type="text" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none font-semibold">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" data-i18n="label_details">รายละเอียด *</label>
                    <textarea id="adminMessageInput" rows="3" required class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none"></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeContactAdminModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer" data-i18n="btn_cancel">ยกเลิก</button>
                    <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-semibold cursor-pointer" data-i18n="btn_send_admin">ส่งเรื่องให้แอดมิน</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: เข้าสู่ระบบ / สมัครสมาชิก -->
    <div id="authModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-200">
            <div class="grid grid-cols-2 bg-slate-100 p-1 m-4 rounded-xl">
                <button id="tabLoginBtn" onclick="switchAuthTab('login')" class="py-2 rounded-lg font-semibold text-sm transition cursor-pointer bg-white text-academic-blue shadow-xs" data-i18n="nav_login">เข้าสู่ระบบ</button>
                <button id="tabRegisterBtn" onclick="switchAuthTab('register')" class="py-2 rounded-lg font-semibold text-sm transition cursor-pointer text-slate-600" data-i18n="btn_register">สมัครสมาชิก</button>
            </div>

            <div class="px-6 pb-6 pt-1 text-sm">
                <form id="loginForm" onsubmit="handleLogin(event)" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Email</label>
                        <input id="loginEmail" type="email" required value="test@example.com" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Password</label>
                        <input id="loginPassword" type="password" required value="password123" class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg text-xs text-slate-700 border border-blue-200 space-y-1">
                        <div><strong>บัญชีนักศึกษาทั่วไป:</strong> <code class="text-academic-blue font-bold">test@example.com</code></div>
                        <div><strong>บัญชีติวเตอร์ ("เสี่ยไอซ์"):</strong> <code class="text-emerald-700 font-bold">tutor@example.com</code></div>
                        <div>รหัสผ่าน: <code class="font-bold">password123</code></div>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" onclick="closeAuthModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer" data-i18n="btn_cancel">ยกเลิก</button>
                        <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer" data-i18n="nav_login">เข้าสู่ระบบ</button>
                    </div>
                </form>

                <form id="registerForm" onsubmit="handleRegister(event)" class="hidden space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">ชื่อที่ใช้แสดง *</label>
                        <input id="regName" type="text" required placeholder="เช่น กิตติพงศ์" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">อีเมล *</label>
                        <input id="regEmail" type="email" required placeholder="student@cmu.ac.th" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">รหัสผ่าน (อย่างน้อย 6 ตัวอักษร) *</label>
                        <input id="regPassword" type="password" required minlength="6" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 focus:border-academic-blue focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">สาขาวิชาที่เรียน *</label>
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
                        <button type="button" onclick="closeAuthModal()" class="w-1/3 py-2.5 rounded-lg border border-slate-300 text-slate-600 font-semibold cursor-pointer" data-i18n="btn_cancel">ยกเลิก</button>
                        <button type="submit" class="w-2/3 py-2.5 rounded-lg bg-academic-blue hover:bg-academic-blueHover text-white font-semibold cursor-pointer" data-i18n="btn_register">สมัครสมาชิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- กล่องแจ้งเตือน Toast -->
    <div id="toastBox" class="fixed bottom-5 right-5 z-50 hidden max-w-sm px-5 py-3 rounded-xl shadow-lg text-white font-medium text-sm transition-all"></div>

    <script>
        // เช็ค Token ให้ชัวร์ว่าไม่ใช่ค่าขยะ
        let rawToken = localStorage.getItem('auth_token');
        let authToken = (rawToken === 'undefined' || rawToken === 'null' || !rawToken) ? null : rawToken;
        if (!authToken) localStorage.removeItem('auth_token');

        let currentUser = JSON.parse(localStorage.getItem('cached_user') || 'null');
        let currentLang = localStorage.getItem('app_lang') || 'th';
        let purchasedCourseIds = new Set();

        const i18nDict = {
            th: {
                promo_prefix: 'โค้ดส่วนลดช่วงสอบ: คลิกคัดลอก',
                nav_search_courses: 'ค้นหาคอร์สเรียน',
                nav_my_courses: 'คอร์สของฉัน',
                nav_apply_tutor: 'สมัครเป็นติวเตอร์',
                nav_create_course: '+ ลงขายคอร์สเรียน',
                nav_contact_admin: 'ติดต่อแอดมิน',
                nav_profile: 'โปรไฟล์',
                nav_cart: 'ตะกร้า',
                nav_login: 'เข้าสู่ระบบ',
                nav_logout: 'ออก',
                btn_register: 'สมัครสมาชิก',
                btn_cancel: 'ยกเลิก',
                btn_send_admin: 'ส่งเรื่องให้แอดมิน',
                btn_apply_code: 'ใช้โค้ด',
                btn_checkout: 'ยืนยันการชำระเงิน',
                cart_subtitle: 'สามารถกรอกโค้ดส่วนลดเพิ่มได้ (โค้ดแต่ละรหัสใช้ได้ 1 ครั้ง)',
                cart_original_total: 'ราคารวมปกติ',
                cart_net_total: 'ยอดชำระสุทธิ:',
                admin_modal_desc: 'สำหรับการแจ้งแก้ไขราคาคอร์ส แก้ไขรายละเอียดคอร์สที่วางขายแล้ว การขอจัดโปรโมชันลดราคา หรือแจ้งปัญหาการใช้งาน (ดำเนินการโดยแอดมินเท่านั้น)',
                label_topic: 'หัวข้อติดต่อ *',
                label_details: 'รายละเอียด *',
                footer_subtitle: '— แพลตฟอร์มคอร์สเรียนและติวเตอร์คณะวิศวกรรมศาสตร์',
                hero_badge: 'แพลตฟอร์มคอร์สเรียนคณะวิศวกรรมศาสตร์',
                hero_title: 'ค้นหาคอร์สติวและสรุปเนื้อหาจากรหัสวิชา 6 หลัก',
                hero_subtitle: 'เรียนกับติวเตอร์รุ่นพี่ในคณะวิศวกรรมศาสตร์ที่ผ่านการยืนยันตัวตนและผลการเรียน พร้อมคลิปวิดีโอและเอกสาร PDF',
                btn_search: 'ค้นหาวิชา',
                popular_codes: 'รหัสวิชายอดนิยม:',
                dept_filter_title: 'แยกตามสาขาวิชา (รหัสขึ้นต้น 3 หลัก)',
                all_depts: 'ทุกสาขาวิชา',
                unit_hours: 'ชั่วโมง',
                unit_baht: 'บาท',
                btn_detail: 'ดูรายละเอียด',
                btn_buy: 'สั่งซื้อ',
                btn_purchased_watch: '✓ ซื้อแล้ว (เข้าเรียน)',
                btn_back_catalog: '← ย้อนกลับไปหน้ารวมคอร์สเรียน',
                label_course_hours: 'ชั่วโมงคอร์ส',
                label_course_duration: 'อายุคอร์สเรียน',
                label_suitable_for: 'เหมาะสำหรับ :',
                label_course_desc: 'รายละเอียดคอร์ส :',
                btn_contact_tutor: '💬 ติดต่อผู้สอน',
                section_syllabus: 'หัวข้อบทเรียนในคอร์ส (Syllabus)',
                section_instructor_reviews: 'ข้อมูลผู้สอนและรีวิวจากผู้เรียน',
                write_review_label: 'เขียนรีวิวให้คอร์สเรียนนี้:',
                my_courses_title: 'คอร์สเรียนที่ซื้อแล้ว & คลิปสอนของฉัน',
                tab_purchased: '📚 คอร์สที่ฉันซื้อแล้ว',
                tab_uploaded: '📹 คลิปสอนที่ฉันลงขาย',
                profile_page_title: 'ตั้งค่าโปรไฟล์และรูปภาพของฉัน',
                apply_tutor_title: 'สมัครและยืนยันตัวตนเพื่อเป็นติวเตอร์คณะวิศวกรรมศาสตร์',
                create_course_title: 'อัปโหลดคลิปวิดีโอและลงขายคอร์สเรียนใหม่'
            },
            en: {
                promo_prefix: 'Exam Promo Codes: Click to copy',
                nav_search_courses: 'Browse Courses',
                nav_my_courses: 'My Courses',
                nav_apply_tutor: 'Become a Tutor',
                nav_create_course: '+ Publish Course',
                nav_contact_admin: 'Contact Admin',
                nav_profile: 'Profile',
                nav_cart: 'Cart',
                nav_login: 'Sign In',
                nav_logout: 'Logout',
                btn_register: 'Register',
                btn_cancel: 'Cancel',
                btn_send_admin: 'Submit to Admin',
                btn_apply_code: 'Apply',
                btn_checkout: 'Confirm & Checkout',
                cart_subtitle: 'Multiple promo codes supported (1 use per code)',
                cart_original_total: 'Original Subtotal',
                cart_net_total: 'Net Total:',
                admin_modal_desc: 'For requesting course price/content edits after publishing, discount promotions, or general support (handled by Admin only).',
                label_topic: 'Subject *',
                label_details: 'Details *',
                footer_subtitle: '— Engineering Peer-to-Peer Video Tutoring Platform',
                hero_badge: 'Engineering Peer-to-Peer Learning Platform',
                hero_title: 'Search Engineering Courses by 6-Digit Course Code',
                hero_subtitle: 'Learn from verified top-performing engineering seniors with HD video lessons and downloadable PDF sheets.',
                btn_search: 'Search',
                popular_codes: 'Popular Codes:',
                dept_filter_title: 'Filter by Engineering Department (3-Digit Prefix)',
                all_depts: 'All Departments',
                unit_hours: 'Hours',
                unit_baht: 'THB',
                btn_detail: 'Details',
                btn_buy: 'Enroll',
                btn_purchased_watch: '✓ Enrolled (Watch)',
                btn_back_catalog: '← Back to Course Catalog',
                label_course_hours: 'Course Length',
                label_course_duration: 'Access Period',
                label_suitable_for: 'Suitable For :',
                label_course_desc: 'Course Description :',
                btn_contact_tutor: '💬 Contact Tutor',
                section_syllabus: 'Course Syllabus',
                section_instructor_reviews: 'Instructor & Student Reviews',
                write_review_label: 'Write a review for this course:',
                my_courses_title: 'My Purchased Courses & Tutor Studio',
                tab_purchased: '📚 Purchased Courses',
                tab_uploaded: '📹 My Published Courses',
                profile_page_title: 'Edit My Profile & Avatar',
                apply_tutor_title: 'Tutor Identity & Grade Verification',
                create_course_title: 'Upload Video Clip & Publish New Course'
            }
        };

        function t(key) {
            return (i18nDict[currentLang] && i18nDict[currentLang][key]) || i18nDict.th[key] || key;
        }

        function toggleLanguage() {
            currentLang = currentLang === 'th' ? 'en' : 'th';
            localStorage.setItem('app_lang', currentLang);
            applyLanguageUI();
            if (typeof window.onPageLanguageChanged === 'function') {
                window.onPageLanguageChanged();
            }
        }

        function applyLanguageUI() {
            const btn = document.getElementById('langSwitcherBtn');
            if (btn) btn.textContent = currentLang === 'th' ? '🌐 TH' : '🌐 EN';
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (key) el.textContent = t(key);
            });
        }

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
            if (couponInput) couponInput.value = code;
            showToast(`คัดลอกโค้ดส่วนลด ${code} เรียบร้อยแล้ว`);
        }

        function parseCourseMeta(course) {
            const v = (course.variants && course.variants.length > 0) ? course.variants[0] : null;
            let parsedVariant = {};
            if (v && v.color) {
                try { parsedVariant = JSON.parse(v.color); } catch (e) {}
            }

            const tutorName = course.tutor_name || (v && v.size ? v.size.split('| OWNER:')[0].trim() : 'เสี่ยไอซ์');
            const ownerEmail = course.owner_email || (v && v.size && v.size.includes('| OWNER:') ? v.size.split('| OWNER:')[1].trim() : '');
            const hoursNum = Number(course.hours_num || parsedVariant.hours_num || 20);
            const duration = course.duration || parsedVariant.duration || '12 เดือน';
            const tutorDepartment = course.tutor_department || parsedVariant.tutor_department || 'สาขาวิศวกรรมคอมพิวเตอร์';
            const examType = course.exam_type || parsedVariant.exam_type || 'Midterm + Final';
            const suitableFor = course.suitable_for || parsedVariant.suitable_for || `นักศึกษาคณะวิศวกรรมศาสตร์ที่ลงเรียนรหัสวิชา ${course.sku || ''}`;
            const syllabus = course.syllabus || parsedVariant.syllabus || 'บทที่ 1: ปูพื้นฐานและสรุปแนวข้อสอบ';
            const pdfUrl = course.pdf_url || parsedVariant.pdf_url || 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
            const videoUrl = course.video_url || parsedVariant.video_url || 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4';

            return {
                tutorName,
                ownerEmail,
                hoursNum,
                hoursFormatted: `${hoursNum} ${t('unit_hours')}`,
                duration,
                tutorDepartment,
                examType,
                suitableFor,
                syllabus,
                pdfUrl,
                videoUrl
            };
        }

        function updateAuthUI() {
            const guestDiv = document.getElementById('guestActions');
            const userDiv = document.getElementById('userActions');
            const btnApplyTutor = document.getElementById('navBtnApplyTutor');
            const btnCreateCourse = document.getElementById('navBtnCreateCourse');
            const navTutorBadge = document.getElementById('navTutorBadge');

            if (authToken && currentUser) {
                const displayName = currentUser.tutor_alias || currentUser.name || 'นักศึกษา';
                const avatarUrl = currentUser.avatar_url || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(displayName) + '&background=0066FF&color=fff&bold=true');

                guestDiv.classList.remove('flex');
                guestDiv.classList.add('hidden');
                
                userDiv.classList.remove('hidden');
                userDiv.classList.add('flex');

                document.getElementById('navUserAvatar').src = avatarUrl;
                document.getElementById('navUserName').textContent = displayName;

                if (currentUser.role === 'tutor') {
                    if (btnApplyTutor) btnApplyTutor.classList.add('hidden');
                    if (btnCreateCourse) btnCreateCourse.classList.remove('hidden');
                    if (navTutorBadge) navTutorBadge.classList.remove('hidden');
                } else {
                    if (btnApplyTutor) btnApplyTutor.classList.remove('hidden');
                    if (btnCreateCourse) btnCreateCourse.classList.add('hidden');
                    if (navTutorBadge) navTutorBadge.classList.add('hidden');
                }
            } else {
                guestDiv.classList.remove('hidden');
                guestDiv.classList.add('flex');
                
                userDiv.classList.add('hidden');
                userDiv.classList.remove('flex');
                
                if (btnApplyTutor) btnApplyTutor.classList.remove('hidden');
                if (btnCreateCourse) btnCreateCourse.classList.add('hidden');
            }
        }

        async function fetchMemberProfile() {
            if (!authToken) return null;
            try {
                const res = await fetch('/api/member-info', {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` }
                });
                if (!res.ok) {
                    logout(false);
                    return null;
                }
                const data = await res.json();
                currentUser = data.user || data;
                localStorage.setItem('cached_user', JSON.stringify(currentUser));
                updateAuthUI();
                return currentUser;
            } catch (err) {
                return null;
            }
        }

        async function fetchPurchasedIds() {
            if (!authToken) return;
            try {
                const res = await fetch('/api/my-courses', {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` }
                });
                if (res.ok) {
                    const data = await res.json();
                    purchasedCourseIds = new Set((data.purchased_ids || []).map(id => Number(id)));
                }
            } catch (err) {}
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
            await performLogin(document.getElementById('loginEmail').value, document.getElementById('loginPassword').value);
        }

        async function performLogin(email, password) {
            try {
                const res = await fetch('/api/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'อีเมลหรือรหัสผ่านไม่ถูกต้อง', true);
                    return;
                }
                
                authToken = data.token;
                localStorage.setItem('auth_token', authToken);
                
                currentUser = data.user;
                localStorage.setItem('cached_user', JSON.stringify(currentUser));
                
                updateAuthUI();
                closeAuthModal();
                showToast(`เข้าสู่ระบบสำเร็จ ยินดีต้อนรับ ${currentUser.tutor_alias || currentUser.name}`);

                await fetchPurchasedIds();
                await fetchCart();
                
                if (typeof window.onAuthChanged === 'function') {
                    window.onAuthChanged();
                }
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
                    body: JSON.stringify(payload)
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

        async function logout(notify = true) {
            if (authToken) {
                try {
                    await fetch('/api/logout', { 
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` }
                    });
                } catch (err) {}
            }

            authToken = null;
            currentUser = null;
            purchasedCourseIds.clear();
            localStorage.clear();
            updateAuthUI();
            document.getElementById('cartBadge').textContent = '0';
            if (notify) showToast('ออกจากระบบเรียบร้อยแล้ว');
            setTimeout(() => { window.location.reload(); }, 800);
        }

        async function addToCart(productId) {
            if (!authToken) {
                showToast('กรุณาเข้าสู่ระบบก่อนสั่งซื้อคอร์สเรียน', true);
                openAuthModal('login');
                return;
            }
            if (purchasedCourseIds.has(Number(productId))) {
                showToast('คุณซื้อคอร์สนี้แล้ว กำลังพาไปหน้าเข้าเรียน...', false);
                window.location.href = '/my-courses';
                return;
            }
            try {
                const res = await fetch('/api/cart', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` },
                    body: JSON.stringify({ product_id: productId, quantity: 1 })
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

        async function removeFromCart(itemId) {
            if (!authToken) return;
            try {
                const res = await fetch(`/api/cart/${itemId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` }
                });
                if (res.ok) {
                    showToast('🗑️ ลบคอร์สเรียนออกจากตะกร้าเรียบร้อยแล้ว');
                    await fetchCart();
                }
            } catch (err) {}
        }

        async function fetchCart() {
            if (!authToken) return;
            try {
                const res = await fetch('/api/cart', {
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` }
                });
                const data = await res.json();
                renderCart(data);
            } catch (err) {}
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

            if (!cart || !cart.items || cart.items.length === 0) {
                badge.textContent = '0';
                totalEl.textContent = `0 ${t('unit_baht')}`;
                origRow.classList.add('hidden');
                discountRow.classList.add('hidden');
                badgesBox.classList.add('hidden');
                container.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-center p-6 text-slate-400"><p class="font-semibold text-slate-600">ยังไม่มีคอร์สเรียนในตะกร้า</p></div>`;
                return;
            }

            badge.textContent = cart.items.length;
            totalEl.textContent = `${Number(cart.total_amount).toLocaleString()} ${t('unit_baht')}`;

            const appliedCoupons = data.applied_coupons || [];
            const discountApplied = Number(data.discount_applied || 0);
            const originalTotal = Number(data.original_total || cart.total_amount);

            if (discountApplied > 0 && appliedCoupons.length > 0) {
                origRow.classList.remove('hidden');
                origRow.classList.add('flex');
                origEl.textContent = `${originalTotal.toLocaleString()} ${t('unit_baht')}`;
                discountRow.classList.remove('hidden');
                discountRow.classList.add('flex');
                discountLabel.textContent = `ส่วนลด (${appliedCoupons.join(', ')})`;
                discountEl.textContent = `-${discountApplied.toLocaleString()} ${t('unit_baht')}`;
                badgesBox.classList.remove('hidden');
                badgesBox.classList.add('flex');
                badgesBox.innerHTML = `<span class="text-xs text-slate-500">โค้ดที่ใช้แล้ว:</span>` + appliedCoupons.map(c => `<span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 font-bold text-xs">✓ ${c}</span>`).join('');
            } else {
                origRow.classList.add('hidden');
                discountRow.classList.add('hidden');
                badgesBox.classList.add('hidden');
            }

            container.innerHTML = cart.items.map(item => {
                const product = item.product || {};
                const courseName = product.name || `คอร์สเรียน #${item.product_id}`;
                const sku = product.sku ? `[${product.sku}] ` : '';
                const imgUrl = (product.images && product.images.length > 0) ? product.images[0].image_url : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=400&q=80';
                const meta = parseCourseMeta(product);

                return `
                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3.5">
                        <img src="${imgUrl}" alt="${courseName}" class="w-20 h-20 rounded-xl object-cover shrink-0 border border-slate-200 bg-slate-900">
                        <div class="flex-1 min-w-0 space-y-1">
                            <h4 class="font-bold text-xs sm:text-sm text-slate-900 line-clamp-2 leading-snug">${sku}${courseName}</h4>
                            <p class="text-xs text-academic-blue font-semibold truncate">👨‍🏫 ผู้สอน: ${meta.tutorName}</p>
                            <div class="pt-1 flex items-center justify-between gap-2">
                                <span class="font-bold text-academic-blue text-sm">${Number(item.price).toLocaleString()} ${t('unit_baht')}</span>
                                <button onclick="removeFromCart(${item.id})" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold text-xs transition cursor-pointer">🗑️ ลบออก</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function openCartDrawer() {
            if (!authToken) {
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
            const code = couponInput.value.trim();
            if (!code) return;
            try {
                const res = await fetch('/api/apply-coupon', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` },
                    body: JSON.stringify({ code })
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถใช้โค้ดส่วนลดได้', true);
                    return;
                }
                couponInput.value = '';
                showToast(data.message || 'ใช้โค้ดส่วนลดสำเร็จ');
                await fetchCart();
            } catch (err) {}
        }

        async function handleCheckout() {
            try {
                const res = await fetch('/api/checkout', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${authToken}` }
                });
                const data = await res.json();
                if (!res.ok) {
                    showToast(data.message || 'ไม่สามารถทำรายการได้', true);
                    return;
                }
                showToast('ชำระเงินสำเร็จ! กำลังพาไปยังหน้าคอร์สของฉัน...');
                closeCartDrawer();
                setTimeout(() => { window.location.href = '/my-courses'; }, 900);
            } catch (err) {}
        }

        function openContactAdminModal(topic = 'ติดต่อแอดมิน') {
            document.getElementById('adminTopicInput').value = topic;
            document.getElementById('adminMessageInput').value = '';
            document.getElementById('contactAdminModal').classList.remove('hidden');
        }
        function closeContactAdminModal() {
            document.getElementById('contactAdminModal').classList.add('hidden');
        }
        function handleSendAdminMessage(e) {
            e.preventDefault();
            closeContactAdminModal();
            showToast('🛠️ ส่งเรื่องถึงแอดมินเรียบร้อยแล้ว แอดมินจะดำเนินการตรวจสอบให้โดยเร็วที่สุด');
        }

        document.addEventListener('DOMContentLoaded', async () => {
            applyLanguageUI();
            updateAuthUI();
            if (authToken) {
                await fetchMemberProfile();
                await fetchPurchasedIds();
                await fetchCart();
            }
        });
    </script>
    @yield('scripts')
</body>
</html>