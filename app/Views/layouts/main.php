<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'ERP System' ?> | EXCEL COLLEGE</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" style="display: flex; flex-direction: column;">
            <div class="sidebar-header" style="background: var(--header-deep); border-bottom: 1px solid rgba(255,255,255,0.1);">
                <svg viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                </svg>
                <span style="color: #fff;">EXCEL ERP</span>
            </div>
            <ul class="sidebar-menu" style="flex: 1; display: flex; flex-direction: column;">
                <li>
                    <a href="<?= base_url('dashboard') ?>" class="menu-item <?= current_url() == base_url('dashboard') ? 'active' : '' ?>">
                        <i>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        </i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('library') ?>" class="menu-item <?= str_contains(current_url(), 'library') ? 'active' : '' ?>">
                        <i>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </i>
                        <span>Library</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('exam') ?>" class="menu-item <?= str_contains(current_url(), 'exam') ? 'active' : '' ?>">
                        <i>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                        </i>
                        <span>Examination</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('hostel') ?>" class="menu-item <?= str_contains(current_url(), 'hostel') ? 'active' : '' ?>">
                        <i>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        </i>
                        <span>Hostel</span>
                    </a>
                </li>
                <li style="margin-top: auto;">
                    <a href="<?= base_url('logout') ?>" class="menu-item logout">
                        <i>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper">
            <header class="top-nav">
                <div class="top-left" style="display: flex; align-items: center; gap: 12px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#50C878" stroke-width="2" width="24" height="24" style="cursor:pointer;">
                        <path d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <div style="line-height: 1.1;">
                        <div style="font-weight: 700; font-size: 1rem; color: #013220; letter-spacing: 0.5px;">EXCEL COLLEGE OF ENGINEERING & TECHNOLOGY</div>
                        <div style="font-size: 0.72rem; color: #50C878; font-weight: 600;">(AN AUTONOMOUS INSTITUTION)</div>
                    </div>
                </div>
                
                <div class="top-right" style="display: flex; align-items: center; gap: 20px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 5px 12px; border-radius: 4px; font-size: 0.75rem; color: #013220; cursor: pointer; font-weight: 600;">
                        AT: 2025-2026 Even Semester <svg viewBox="0 0 20 20" fill="currentColor" width="12" height="12" style="display:inline; margin-left:4px;"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px; color: #64748b;">
                        <!-- Dynamic Role Badge -->
                        <?php
                            $roleColors = [
                                'admin'   => ['bg' => '#013220', 'text' => '#fff', 'label' => 'ADMIN'],
                                'faculty' => ['bg' => '#1e40af', 'text' => '#fff', 'label' => 'FACULTY'],
                                'student' => ['bg' => '#50C878', 'text' => '#fff', 'label' => 'STUDENT'],
                            ];
                            $rc = $roleColors[session('user_role')] ?? ['bg' => '#64748b', 'text' => '#fff', 'label' => 'USER'];
                        ?>
                        <div style="display:flex; align-items:center; gap:8px; background:#f8fafc; border:1px solid #e2e8f0; padding:5px 12px; border-radius:30px;">
                            <div style="width: 30px; height: 30px; border-radius: 50%; background: <?= $rc['bg'] ?>; color: <?= $rc['text'] ?>; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">
                                <?= strtoupper(substr(session('username') ?? 'U', 0, 2)) ?>
                            </div>
                            <div style="line-height:1.2;">
                                <div style="font-size:0.75rem; font-weight:700; color:#013220;"><?= session('username') ?></div>
                                <div style="font-size:0.6rem; font-weight:700; color:<?= $rc['bg'] ?>; text-transform:uppercase;"><?= $rc['label'] ?></div>
                            </div>
                        </div>
                        <a href="<?= base_url('logout') ?>" title="Logout" style="color: inherit;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#e53e3e" stroke-width="2" width="20" height="20" style="cursor:pointer;"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </a>
                    </div>
                </div>
            </header>

            <div class="breadcrumb-tabs" style="display: flex; justify-content: space-between; align-items: center; padding-right: 15px;">
                <div style="display: flex; align-items: center;">
                    <span class="tab-label">Recent</span>
                    <div class="tab-pill active">Dashboard <i>×</i></div>
                    <div class="tab-pill">Library Master <i>×</i></div>
                </div>
                
                <!-- Compact Flash News on Right -->
                <div class="compact-news" style="display: flex; align-items: center; background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; height: 32px; width: 450px; overflow: hidden; position: relative;">
                    <div style="background: #013220; color: #fff; padding: 0 15px; height: 100%; display: flex; align-items: center; font-size: 0.7rem; font-weight: 700; position: relative; z-index: 2; clip-path: polygon(0 0, 85% 0, 100% 100%, 0% 100%);">
                        FLASH NEWS
                    </div>
                    <div style="flex: 1; padding-left: 10px; font-size: 0.75rem; color: #64748b; white-space: nowrap;">
                        <marquee behavior="scroll" direction="left" scrollamount="4">
                            📢 Semester examinations begin from May 15th, 2026. Please complete the library book returns on schedule.
                        </marquee>
                    </div>
                </div>
            </div>

            <main class="content-area">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Global Confirmation Modal -->
    <div id="confirmModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; transition: 0.3s ease;">
        <div style="background:#fff; width:440px; border-radius:14px; overflow:hidden; box-shadow:0 15px 50px rgba(0,0,0,0.2); animation: slideUp 0.3s ease-out;">
            <div style="padding: 18px 25px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #f1f5f9;">
                <h3 id="confirmTitle" style="font-size:1.1rem; font-weight:700; color:#013220; margin:0;">Confirm Action</h3>
                <span onclick="closeConfirmModal()" style="cursor:pointer; font-size:1.6rem; color:#94a3b8; line-height:1;">&times;</span>
            </div>
            <div style="padding: 25px;">
                <p id="confirmMessage" style="color:#64748b; font-size:0.9rem; margin:0; line-height:1.5;">Are you sure you want to proceed with this action?</p>
            </div>
            <div style="padding: 15px 25px; background: #f8fafc; display:flex; justify-content:flex-end; gap:12px;">
                <button onclick="closeConfirmModal()" style="padding:10px 24px; border:1px solid #e2e8f0; background:white; color:#64748b; border-radius:60px; font-weight:600; cursor:pointer; font-size:0.85rem;">Cancel</button>
                <button id="confirmBtn" style="padding:10px 24px; border:none; background:#013220; color:white; border-radius:60px; font-weight:600; cursor:pointer; font-size:0.85rem;">Yes, Confirm</button>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 100000; display: flex; flex-direction: column; gap: 10px;"></div>

    <style>
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }

        .toast {
            min-width: 300px;
            padding: 16px 20px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            animation: slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-left: 5px solid #013220;
            position: relative;
            overflow: hidden;
        }
        .toast.success { border-left-color: #50C878; color: #013220; }
        .toast.error { border-left-color: #ff6b6b; color: #b91c1c; }
        
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(0,0,0,0.1);
            width: 100%;
        }
        .toast-progress-bar {
            height: 100%;
            background: inherit;
            width: 0%;
            transition: width linear;
        }
    </style>

    <script>
        let currentConfirmCallback = null;

        function openConfirmModal(title, message, callback) {
            document.getElementById('confirmTitle').innerText = title;
            document.getElementById('confirmMessage').innerText = message;
            document.getElementById('confirmModal').style.display = 'flex';
            currentConfirmCallback = callback;
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        document.getElementById('confirmBtn').onclick = function() {
            if (currentConfirmCallback) currentConfirmCallback();
            closeConfirmModal();
        };

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icon = type === 'success' ? '✅' : '❌';
            
            toast.innerHTML = `
                <span>${icon}</span>
                <div style="flex: 1;">${message}</div>
                <div class="toast-progress"><div class="toast-progress-bar" style="background: ${type === 'success' ? '#50C878' : '#ff6b6b'}"></div></div>
            `;
            
            container.appendChild(toast);
            
            const progressBar = toast.querySelector('.toast-progress-bar');
            setTimeout(() => progressBar.style.width = '100%', 10);
            progressBar.style.transitionDuration = '4000ms';

            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.5s forwards';
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }

        $(document).ready(function() {
            // Check for flash messages and show as toasts
            <?php if(session()->getFlashdata('success')): ?>
                showToast("<?= addslashes(session()->getFlashdata('success')) ?>", 'success');
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('error')): ?>
                showToast("<?= addslashes(session()->getFlashdata('error')) ?>", 'error');
            <?php endif; ?>

            // "Search inside search" logic
            $('.search-header input').on('keyup', function() {
                var index = $(this).closest('td').index();
                var value = $(this).val().toLowerCase();
                
                $('table tbody tr').each(function() {
                    var cellText = $(this).find('td').eq(index).text().toLowerCase();
                    if (cellText.indexOf(value) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
</body>
</html>
