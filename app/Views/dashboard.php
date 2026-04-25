<?php $this->extend('layouts/main') ?>

<?php $this->section('content') ?>
<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
    <h2 style="font-size: 1.1rem; color: #013220; font-weight: 800; letter-spacing: 0.5px;">
        <?php 
            if($role === 'admin') echo 'Administrative';
            elseif($role === 'faculty') echo 'Faculty';
            else echo 'Student';
        ?> Intelligence Dashboard
    </h2>
    <div style="display:flex; gap:8px;">
        <?php if(session('user_role') === 'admin'): ?>
        <button class="btn btn-primary" onclick="openModal('newUserModal')" style="background: #013220; color: #fff; font-size: 0.72rem; border-radius: 4px; padding: 8px 20px; font-weight: 700; cursor: pointer;">+ NEW STUDENT</button>
        <?php elseif(session('user_role') === 'faculty'): ?>
        <button class="btn btn-primary" onclick="openModal('newUserModal')" style="background: #013220; color: #fff; font-size: 0.72rem; border-radius: 4px; padding: 8px 20px; font-weight: 700; cursor: pointer;">+ ADD MENTEE</button>
        <?php endif; ?>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
    <!-- Students -->
    <div style="background: #013220; padding: 15px; border-radius: 10px; color: #fff; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 12px rgba(1,50,32,0.2);">
        <div style="background: rgba(255,255,255,0.1); width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <div>
            <div style="font-size: 0.65rem; font-weight: 600; opacity: 0.7; text-transform: uppercase;">
                <?php 
                    if($role === 'faculty') echo 'My Mentees';
                    elseif($role === 'student') echo 'My Attendance';
                    else echo 'Total Students';
                ?>
            </div>
            <div style="font-size: 1.2rem; font-weight: 800;">
                <?php if($role === 'student'): ?>
                    <?= $studentStats['attendance'] ?>% <span style="font-size: 0.7rem; color: #fff; opacity: 0.8; font-weight: 500;">(<?= $studentStats['att_status'] ?>)</span>
                <?php else: ?>
                    <?= $total_students ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Library -->
    <div style="background: #fff; border: 1px solid #e2e8f0; padding: 15px; border-radius: 10px; display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="background: #f0fdf4; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#013220" stroke-width="2" width="20" height="20"><path d="M4 19.5A2.5 2.5 0 016.5 17H20M4 19.5A2.5 2.5 0 006.5 22H20M4 19.5V3a2.5 2.5 0 012.5-2.5H20v16.5"/></svg>
        </div>
        <div>
            <div style="font-size: 0.65rem; font-weight: 600; color: #94a3b8; text-transform: uppercase;">Library Circulation</div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #013220;">
                <?php if($role === 'student'): ?>
                    <?= $libStats['my_books'] ?> / 6 <span style="font-size: 0.7rem; color: #64748b; font-weight: 500;">Books</span>
                <?php else: ?>
                    <?= $libStats['issued_books'] ?> / <?= $libStats['total_books'] ?> <span style="font-size: 0.7rem; color: #64748b; font-weight: 500;">Books</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Exams -->
    <div style="background: #fff; border: 1px solid #e2e8f0; padding: 15px; border-radius: 10px; display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="background: #fff7ed; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" width="20" height="20"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
        </div>
        <div>
            <div style="font-size: 0.65rem; font-weight: 600; color: #94a3b8; text-transform: uppercase;">
                <?php 
                    if($role === 'admin') echo 'Unpublished Exams';
                    elseif($role === 'faculty') echo 'Pending Papers';
                    else echo 'Upcoming Exams';
                ?>
            </div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #ea580c;">
                <?php 
                    if($role === 'admin') echo ($examStats['pending_results'] ?? 0);
                    elseif($role === 'faculty') echo ($examStats['pending_papers'] ?? 0);
                    else echo ($examStats['upcoming'] ?? 0);
                ?> 
                <span style="font-size: 0.7rem; color: #64748b; font-weight: 500;">Units</span>
            </div>
        </div>
    </div>

    <!-- Hostel -->
    <div style="background: #fff; border: 1px solid #e2e8f0; padding: 15px; border-radius: 10px; display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
        <div style="background: #f0f9ff; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#0369a1" stroke-width="2" width="20" height="20"><path d="M3 21h18M3 10l9-7 9 7v11H3V10z" /><path d="M9 21V12h6v9" /></svg>
        </div>
        <div>
            <div style="font-size: 0.65rem; font-weight: 600; color: #94a3b8; text-transform: uppercase;">
                <?= ($role === 'admin' ? 'Hostel Occupancy' : ($role === 'faculty' ? 'Pending Approvals' : 'Hostel Status')) ?>
            </div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #013220;">
                <?php if($role === 'admin'): ?>
                    <?= $hostelStats['total_occupied'] ?> <span style="font-size: 0.7rem; color: #ef4444; font-weight: 700;">(<?= $hostelStats['pending_requests'] ?> REQ)</span>
                <?php elseif($role === 'faculty'): ?>
                    <?= $hostelStats['pending_requests'] ?> <span style="font-size: 0.7rem; color: #64748b; font-weight: 500;">Requests</span>
                <?php else: ?>
                    <?= $hostelStats['my_status'] ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff;">
    <div class="card-body" style="padding: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: #fff; border-bottom: 1px solid #f1f5f9;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #718096;">
                <select style="padding: 3px 8px; border-radius: 4px; border: 1px solid #e2e8f0; background: #fff; font-size: 0.8rem;">
                    <option>10</option>
                </select>
                records
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <div style="position: relative;">
                    <input type="text" placeholder="Search" style="padding: 4px 12px 4px 30px; border-radius: 4px; width: 180px; border: 1px solid #e2e8f0; font-size: 0.75rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="2" width="14" height="14" style="position: absolute; left: 10px; top: 7px;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                </div>
                <div style="width: 28px; height: 28px; background: #2f855a; color: #fff; border-radius: 4px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8M10 9h4"/></svg>
                </div>
            </div>
        </div>

        <div class="table-container" style="width: 100%; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 800px;">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;" class="col-id">ID</th>
                        <th style="width: 140px;"><?= $role === 'student' ? 'ROLL NO' : 'EMP ID' ?></th>
                        <th style="width: 220px;">NAME</th>
                        <th style="width: 100px; text-align: center;">GENDER</th>
                        <th style="width: 250px;">EMAIL</th>
                        <th style="width: 140px;">USER GROUP</th>
                        <?php if(session('user_role') != 'student'): ?>
                        <th style="width: 100px; text-align: center;" class="col-action">ACTION</th>
                        <?php endif; ?>
                    </tr>
                    <tr class="search-header">
                        <td><input type="text" placeholder="ID"></td>
                        <td><input type="text" placeholder="Search <?= $role === 'student' ? 'Roll' : 'ID' ?>"></td>
                        <td><input type="text" placeholder="Search Name"></td>
                        <td><input type="text" placeholder="G"></td>
                        <td><input type="text" placeholder="Search Email"></td>
                        <td><input type="text" placeholder="Search Group"></td>
                        <?php if(session('user_role') != 'student'): ?>
                        <td></td>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($students)): foreach($students as $student): ?>
                    <tr>
                        <td class="col-id" style="text-align: center;"><?= $student['id'] ?></td>
                        <td><?= $role === 'student' ? 'R-' : 'E-' ?><?= 100 + $student['id'] ?></td>
                        <td style="font-weight: 600; color: #013220;"><?= $student['name'] ?></td>
                        <td style="text-align: center;">Male</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px; background: #f8fafc; padding: 4px 10px; border-radius: 60px; border: 1px solid #edf2f7; width: fit-content; max-width: 230px;">
                                <div style="background: #e2e8f0; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" width="12" height="12"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                                </div>
                                <span style="font-size: 0.75rem; color: #475569; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-weight: 500;" title="<?= $student['email'] ?>">
                                    <?= $student['email'] ?>
                                </span>
                                <div style="display: flex; gap: 4px; margin-left: auto;">
                                    <button onclick="copyToClipboard('<?= $student['email'] ?>')" style="background: none; border: none; padding: 2px; cursor: pointer; color: #94a3b8; display: flex; align-items: center; justify-content: center;" title="Copy Email">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                                    </button>
                                    <a href="mailto:<?= $student['email'] ?>" style="color: #94a3b8; display: flex; align-items: center; justify-content: center; text-decoration: none;" title="Send Mail">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>Student</td>
                        <?php if(session('user_role') != 'student'): ?>
                        <td class="col-action">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <div style="width: 26px; height: 26px; background: #99a9bf; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" width="14" height="14" style="transform: rotate(-45deg);"><rect x="2" y="9" width="20" height="6" rx="3" /></svg>
                                </div>
                                <div style="width: 26px; height: 26px; background: #ff6b6b; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="openConfirmModal('Delete User', 'Delete this user profile?', function(){ window.location.href='<?= base_url('dashboard/delete/'.$student['id']) ?>'; })">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" width="14" height="14">
                                        <path d="M5 10c0-1.1.9-2 2-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V10z" />
                                        <path d="M3 6h18m-14 0v-1a2 2 0 012-2h6a2 2 0 012 2v1" />
                                        <line x1="10" y1="13" x2="14" y2="13" stroke-width="2" />
                                        <line x1="10" y1="17" x2="14" y2="17" stroke-width="2" />
                                    </svg>
                                </div>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" style="text-align:center; padding: 20px; color: #a0aec0;">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for New User -->
<div id="newUserModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:450px; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700;">Add New Student User</h3>
            <span onclick="closeModal('newUserModal')" style="cursor:pointer; font-size:1.5rem;">&times;</span>
        </div>
        <form action="<?= base_url('dashboard/create') ?>" method="POST" style="padding:20px;">
            <div style="margin-bottom:15px;">
                <label style="displayblock; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">FULL NAME</label>
                <input type="text" name="name" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">EMAIL ADDRESS</label>
                <input type="email" name="email" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <button type="submit" style="width:100%; background:#013220; color:#fff; border:none; padding:12px; border-radius:60px; font-weight:800; cursor:pointer; transition:0.3s;">SAVE USER</button>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.right = '20px';
        toast.style.background = '#013220';
        toast.style.color = '#fff';
        toast.style.padding = '10px 20px';
        toast.style.borderRadius = '6px';
        toast.style.fontSize = '0.8rem';
        toast.style.fontWeight = '600';
        toast.style.zIndex = '10000';
        toast.textContent = 'Email copied to clipboard!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}
</script>

<?php $this->endSection() ?>
