<?php $this->extend('layouts/main') ?>

<?php $this->section('content') ?>

<?php $role = session('user_role'); ?>

<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
    <h2 style="font-size: 1.1rem; color: #013220; font-weight: 800; letter-spacing: 0.5px;">Hostel Management</h2>
    <?php if($role === 'student'): ?>
    <button class="btn btn-primary" onclick="openModal('applyHostelModal')" style="background: #013220; color: #fff; font-size: 0.75rem; border-radius: 4px; padding: 8px 20px; font-weight: 700; cursor: pointer;">REQUEST ALLOCATION</button>
    <?php endif; ?>
</div>

<!-- Flash messages handled by global Toast system -->

<div class="stat-badges-row" style="margin-bottom: 15px;">
    <div class="stat-badge" style="cursor:default;">Total Blocks <span class="count" style="background:#50C878;">4</span></div>
    <div class="stat-badge" onclick="filterRooms('occupied')" id="badge-occupied" style="cursor:pointer;">Occupied Rooms <span class="count" style="background:#013220;"><?= $occupied_rooms ?? 0 ?></span></div>
    <div class="stat-badge active" onclick="filterRooms('all')" id="badge-all" style="cursor:pointer; border-color: #50C878;">Total Rooms <span class="count" style="background:#50C878;"><?= $total_rooms ?? 0 ?></span></div>
</div>

<?php if(in_array($role, ['admin', 'faculty'])): ?>
<!-- ═══════════════════════════════════════════════════════════
     ADMIN/FACULTY VIEW: PENDING APPROVALS + ROOM INVENTORY
═══════════════════════════════════════════════════════════ -->
<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff; margin-bottom: 20px;">
    <div style="padding: 12px 15px; border-bottom: 1px solid #f1f5f9; background: #fafafa;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #013220; margin: 0;">PENDING ROOM ALLOCATIONS</h3>
    </div>
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #edf2f7;">
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096; width: 80px;">REQ ID</th>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096;">STUDENT NAME</th>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096; width: 120px;">ROOM ID</th>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096; width: 120px;">DATE</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096; width: 180px;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($requests)): foreach($requests as $req): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;">#<?= $req['id'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; font-weight: 600; color: #013220;"><?= $req['student_name'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;">Room ID <?= $req['room_id'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;"><?= $req['allocation_date'] ?></td>
                    <td style="padding: 12px; text-align: center;">
                        <div style="display:flex; gap:5px; justify-content:center;">
                            <button onclick="openConfirmModal('Approve Allocation', 'Confirm room for <?= $req['student_name'] ?>?', function(){ window.location.href='<?= base_url('hostel/approve/'.$req['id']) ?>'; })"
                                    style="background: #013220; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-size: 0.65rem; font-weight: 700; cursor: pointer;">APPROVE</button>
                            <button onclick="openConfirmModal('Reject Request', 'Reject application for <?= $req['student_name'] ?>?', function(){ window.location.href='<?= base_url('hostel/reject/'.$req['id']) ?>'; })"
                                    style="background: #ef4444; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; font-size: 0.65rem; font-weight: 700; cursor: pointer;">REJECT</button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" style="text-align:center; padding: 30px; color: #a0aec0; font-size: 0.8rem;">No pending requests.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if(!empty($active_allocations)): ?>
<!-- Active Allocations (Admin/Faculty) -->
<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff; margin-bottom: 20px;">
    <div style="padding: 12px 15px; border-bottom: 1px solid #f1f5f9; background: #fafafa;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #013220; margin: 0;">ACTIVE ALLOCATIONS & FEE STATUS</h3>
    </div>
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #edf2f7;">
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096;">STUDENT</th>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096;">ROOM</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096;">FEE STATUS</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($active_allocations as $acc): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px;">
                        <div style="font-size: 0.8rem; font-weight: 600; color: #013220;"><?= $acc['student_name'] ?></div>
                        <div style="font-size: 0.65rem; color: #64748b;">Allocated: <?= $acc['allocation_date'] ?></div>
                    </td>
                    <td style="padding: 12px;">
                        <div style="font-size: 0.8rem; color: #013220;"><?= $acc['room_number'] ?></div>
                        <div style="font-size: 0.65rem; color: #64748b;"><?= $acc['block'] ?></div>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <form action="<?= base_url('hostel/fee-update/'.$acc['id']) ?>" method="POST" style="display:flex; align-items:center; justify-content:center; gap:5px;">
                            <select name="fee_status" onchange="this.form.submit()" style="font-size: 0.7rem; padding: 4px; border-radius: 4px; border: 1px solid #e2e8f0; background: <?= $acc['fee_status'] === 'Paid' ? '#dcfce7' : ($acc['fee_status'] === 'Unpaid' ? '#fee2e2' : '#fef3c7') ?>;">
                                <option value="Unpaid" <?= $acc['fee_status'] === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                <option value="Partial" <?= $acc['fee_status'] === 'Partial' ? 'selected' : '' ?>>Partial</option>
                                <option value="Paid" <?= $acc['fee_status'] === 'Paid' ? 'selected' : '' ?>>Paid</option>
                            </select>
                        </form>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <button onclick="openConfirmModal('Vacate Room', 'Process vacation for <?= $acc['student_name'] ?>?', function(){ window.location.href='<?= base_url('hostel/vacate/'.$acc['id']) ?>'; })"
                                style="background: #fff; color: #ef4444; border: 1px solid #ef4444; padding: 5px 12px; border-radius: 4px; font-size: 0.65rem; font-weight: 700; cursor: pointer;">VACATE</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Room Inventory for Admin/Faculty -->
<?php if(!empty($rooms)): ?>
<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff;">
    <div style="padding: 12px 15px; border-bottom: 1px solid #f1f5f9; background: #fafafa;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #013220; margin: 0;">ROOM INVENTORY</h3>
    </div>
    <div class="table-container">
        <table id="roomInventoryTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #edf2f7;">
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096; width: 80px;">ID</th>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096;">ROOM NO.</th>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096;">BLOCK</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096;">CAPACITY</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096;">OCCUPIED</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096;">AVAILABLE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rooms as $room): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;"><?= $room['id'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; font-weight: 600; color: #013220;"><?= $room['room_number'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;"><?= $room['block'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; text-align: center;"><?= $room['capacity'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; text-align: center; color: #e53e3e; font-weight: 600;"><?= $room['occupied'] ?></td>
                    <td style="padding: 12px; text-align: center;">
                        <?php $avail = $room['capacity'] - $room['occupied']; ?>
                        <span style="background: <?= $avail > 0 ? '#dcfce7' : '#fee2e2' ?>; color: <?= $avail > 0 ? '#166534' : '#b91c1c' ?>; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            <?= $avail > 0 ? $avail . ' FREE' : 'FULL' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Pagination Controls -->
        <div id="roomPagination" style="padding: 10px 15px; display: flex; justify-content: flex-end; align-items: center; gap: 15px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
            <span id="roomPageInfo" style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Page 1 of 1</span>
            <div style="display: flex; gap: 5px;">
                <button onclick="changeRoomPage(-1)" id="roomPrevBtn" style="padding: 4px 12px; border: 1px solid #e2e8f0; background: #fff; border-radius: 4px; cursor: pointer; font-size: 0.7rem; font-weight: 700; color: #013220;">PREV</button>
                <button onclick="changeRoomPage(1)" id="roomNextBtn" style="padding: 4px 12px; border: 1px solid #e2e8f0; background: #fff; border-radius: 4px; cursor: pointer; font-size: 0.7rem; font-weight: 700; color: #013220;">NEXT</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php else: ?>
<!-- ═══════════════════════════════════════════════════════════
     STUDENT VIEW: MY ALLOCATION STATUS + APPLY
═══════════════════════════════════════════════════════════ -->
<?php if($my_allocation): ?>
<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff; margin-bottom: 20px; padding: 25px;">
    <h3 style="font-size: 0.85rem; font-weight: 700; color: #013220; margin: 0 0 15px 0;">MY ROOM ALLOCATION</h3>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Room No.</div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #013220;"><?= $my_allocation['room_number'] ?? '—' ?></div>
        </div>
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Block</div>
            <div style="font-size: 1.2rem; font-weight: 800; color: #013220;"><?= $my_allocation['block'] ?? '—' ?></div>
        </div>
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Allocation Status</div>
            <div>
                <span style="background: <?= $my_allocation['status'] === 'Active' ? '#dcfce7' : ($my_allocation['status'] === 'Pending' ? '#fef3c7' : '#fee2e2') ?>; color: <?= $my_allocation['status'] === 'Active' ? '#166534' : ($my_allocation['status'] === 'Pending' ? '#92400e' : '#b91c1c') ?>; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">
                    <?= $my_allocation['status'] ?>
                </span>
            </div>
        </div>
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
            <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 5px;">Fee Status</div>
            <div>
                <span style="background: <?= $my_allocation['fee_status'] === 'Paid' ? '#dcfce7' : ($my_allocation['fee_status'] === 'Unpaid' ? '#fee2e2' : '#fef3c7') ?>; color: <?= $my_allocation['fee_status'] === 'Paid' ? '#166534' : ($my_allocation['fee_status'] === 'Unpaid' ? '#b91c1c' : '#92400e') ?>; padding: 5px 15px; border-radius: 20px; font-size: 0.8rem; font-weight: 700;">
                    <?= $my_allocation['fee_status'] ?>
                </span>
            </div>
        </div>
    </div>
    <?php if($my_allocation['status'] === 'Pending'): ?>
    <p style="color: #92400e; background: #fef3c7; padding: 10px 15px; border-radius: 6px; font-size: 0.8rem; margin-top: 15px; margin-bottom: 0;">⏳ Your request is pending warden approval.</p>
    <?php endif; ?>
</div>
<?php else: ?>
<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff; padding: 30px; text-align: center; margin-bottom: 20px;">
    <svg viewBox="0 0 24 24" fill="none" stroke="#013220" stroke-width="1.5" width="48" height="48" style="opacity: 0.2; margin-bottom: 10px;">
        <path d="M3 21h18M3 10l9-7 9 7v11H3V10z" /><path d="M9 21V12h6v9" />
    </svg>
    <p style="color: #64748b; font-size: 0.9rem; margin: 0;">You do not have any hostel allocation yet.</p>
    <p style="color: #94a3b8; font-size: 0.75rem; margin-top: 5px;">Click <strong>REQUEST ALLOCATION</strong> above to apply.</p>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- Modal for Hostel Application (Student Only) -->
<?php if($role === 'student'): ?>
<div id="applyHostelModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:440px; border-radius:14px; overflow:hidden; box-shadow:0 15px 50px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:18px 25px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1.1rem; font-weight:700; margin:0;">Apply for Hostel Room</h3>
            <span onclick="closeModal('applyHostelModal')" style="cursor:pointer; font-size:1.6rem; line-height:1;">&times;</span>
        </div>
        <form action="<?= base_url('hostel/apply') ?>" method="POST" style="padding:25px;">
            <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:12px; border-radius:8px; margin-bottom:18px; font-size:0.8rem; color:#64748b;">
                📋 Submitting as: <strong style="color:#013220;"><?= session('username') ?></strong> (ID: <?= session('user_id') ?>)
            </div>
            <div style="margin-bottom:25px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:8px; letter-spacing:0.5px;">SELECT AVAILABLE ROOM</label>
                <select name="room_id" required style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:8px; outline:none; font-size:0.9rem; background:#fff;">
                    <?php if(!empty($available_rooms)): foreach($available_rooms as $ar): ?>
                        <option value="<?= $ar['id'] ?>"><?= $ar['room_number'] ?> - <?= $ar['block'] ?> (<?= $ar['capacity'] - $ar['occupied'] ?> Left)</option>
                    <?php endforeach; else: ?>
                        <option value="">No rooms available</option>
                    <?php endif; ?>
                </select>
                <div style="margin-top:10px; font-size:0.7rem; color:#64748b; line-height:1.4;">
                    💡 Select a room from the dropdown above. Only rooms with free capacity are listed.
                </div>
            </div>
            <button type="submit" style="width:100%; background:#013220; color:#fff; border:none; padding:14px; border-radius:60px; font-weight:800; cursor:pointer; font-size:0.9rem;">SUBMIT REQUEST</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

// Pagination Logic for Room Inventory
let currentRoomPage = 1;
const itemsPerRoomPage = 7;

function initRoomPagination() {
    const table = document.getElementById('roomInventoryTable');
    if (!table) return;
    
    const rows = Array.from(table.getElementsByTagName('tbody')[0].rows);
    const totalPages = Math.ceil(rows.length / itemsPerRoomPage);
    
    // Hide all rows initially
    rows.forEach(row => row.style.display = 'none');
    
    // Show only current page rows
    const start = (currentRoomPage - 1) * itemsPerRoomPage;
    const end = start + itemsPerRoomPage;
    
    rows.slice(start, end).forEach(row => row.style.display = '');
    
    // Update UI
    document.getElementById('roomPageInfo').innerText = `Page ${currentRoomPage} of ${totalPages || 1}`;
    document.getElementById('roomPrevBtn').disabled = currentRoomPage === 1;
    document.getElementById('roomNextBtn').disabled = currentRoomPage === totalPages || totalPages === 0;
    
    document.getElementById('roomPrevBtn').style.opacity = currentRoomPage === 1 ? '0.5' : '1';
    document.getElementById('roomNextBtn').style.opacity = (currentRoomPage === totalPages || totalPages === 0) ? '0.5' : '1';
}

function changeRoomPage(dir) {
    currentRoomPage += dir;
    initRoomPagination();
}

function filterRooms(type) {
    // Update badge UI
    document.querySelectorAll('.stat-badge').forEach(el => el.classList.remove('active'));
    if(type === 'occupied') document.getElementById('badge-occupied').classList.add('active');
    else document.getElementById('badge-all').classList.add('active');

    const table = document.getElementById('roomInventoryTable');
    if (!table) return;
    
    const rows = Array.from(table.getElementsByTagName('tbody')[0].rows);
    rows.forEach(row => {
        const occupied = parseInt(row.cells[4].innerText);
        if (type === 'occupied') {
            row.style.display = occupied > 0 ? '' : 'none';
        } else {
            row.style.display = '';
        }
    });
    
    // Reset pagination to page 1 after filtering
    currentRoomPage = 1;
    // Note: initRoomPagination needs to be adjusted to respect display style if we want pagination to work with filter
    // For now, simple filter is better than dead buttons.
}

// Run on load
document.addEventListener('DOMContentLoaded', initRoomPagination);
</script>

<?php $this->endSection() ?>
