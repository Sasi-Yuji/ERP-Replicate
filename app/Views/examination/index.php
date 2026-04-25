<?php $this->extend('layouts/main') ?>

<?php $this->section('content') ?>

<?php $role = session('user_role'); ?>

<style>
/* Hide number input spinners */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
input[type=number] {
    -moz-appearance: textfield;
}
</style>

<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
    <h2 style="font-size: 1.1rem; color: #013220; font-weight: 800; letter-spacing: 0.5px;">Examination Management</h2>
    <div style="display:flex; gap:8px;">
        <?php if($role === 'admin' && $pending_results > 0): ?>
        <button class="btn btn-primary" onclick="openConfirmModal('Publish Results', 'Publish all <?= $pending_results ?> pending results for students to view?', function(){ window.location.href='<?= base_url('exam/publish') ?>'; })" style="background: #ea580c; color: #fff; font-size: 0.75rem; border-radius: 4px; padding: 8px 20px; font-weight: 700; cursor: pointer;">PUBLISH RESULTS</button>
        <?php endif; ?>
        <?php if($role !== 'student'): ?>
        <button class="btn btn-primary" onclick="openModal('uploadMarksModal')" style="background: #013220; color: #fff; font-size: 0.75rem; border-radius: 4px; padding: 8px 20px; font-weight: 700; cursor: pointer;">UPLOAD MARKS</button>
        <?php endif; ?>
    </div>
</div>

<!-- Flash messages handled by global Toast system -->

<div class="stat-badges-row" style="margin-bottom: 15px;">
    <div class="stat-badge" onclick="openModal('examScheduleModal')" style="cursor:pointer; border-color: #50C878;">Upcoming Exams <span class="count" style="background:#50C878;"><?= $total_exams ?? 0 ?></span></div>
    <div class="stat-badge active" onclick="<?= ($role === 'admin' && $pending_results > 0) ? "openConfirmModal('Publish Results', 'Publish all $pending_results pending results?', function(){ window.location.href=\'' . base_url('exam/publish') . '\'; })" : "openModal('examResultsModal')" ?>" style="cursor:pointer; border-color: #50C878;">Pending Results <span class="count" style="background:#013220;"><?= $pending_results ?? 0 ?></span></div>
    <div class="stat-badge" onclick="<?= $role !== 'student' ? "openModal('uploadPaperModal')" : "" ?>" style="<?= $role !== 'student' ? 'cursor:pointer;' : '' ?>">Question Papers <span class="count" style="background:#50C878;"><?= $total_papers ?? 0 ?></span></div>
</div>

<!-- Quick Action Cards -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
    <!-- Exam Schedule -->
    <div onclick="openModal('examScheduleModal')" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 15px;" onmouseover="this.style.borderColor='#013220'" onmouseout="this.style.borderColor='#e2e8f0'">
        <div style="width: 45px; height: 45px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#013220" stroke-width="2" width="24" height="24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        </div>
        <div>
            <div style="font-size: 0.85rem; font-weight: 700; color: #013220;">Exam Schedule</div>
            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 2px;">View timetable</div>
        </div>
    </div>

    <!-- My Results -->
    <div onclick="openModal('examResultsModal')" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 15px;" onmouseover="this.style.borderColor='#013220'" onmouseout="this.style.borderColor='#e2e8f0'">
        <div style="width: 45px; height: 45px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#013220" stroke-width="2" width="24" height="24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
        </div>
        <div>
            <div style="font-size: 0.85rem; font-weight: 700; color: #013220;"><?= $role === 'student' ? 'My Results' : 'All Results' ?></div>
            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 2px;"><?= $role === 'student' ? 'View your marks' : 'View student results' ?></div>
        </div>
    </div>

    <!-- Upload Paper (Faculty/Admin only) -->
    <?php if($role !== 'student'): ?>
    <div onclick="openModal('uploadPaperModal')" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 15px;" onmouseover="this.style.borderColor='#013220'" onmouseout="this.style.borderColor='#e2e8f0'">
        <div style="width: 45px; height: 45px; background: #fff7ed; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" width="24" height="24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M12 18v-6m-3 3l3-3 3 3"/></svg>
        </div>
        <div>
            <div style="font-size: 0.85rem; font-weight: 700; color: #013220;">Upload Papers</div>
            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 2px;">Faculty resources</div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Schedule Exam (Admin only) -->
    <?php if($role === 'admin'): ?>
    <div onclick="openModal('scheduleExamModal')" style="background: #013220; border: 1px solid #013220; border-radius: 8px; padding: 20px; cursor: pointer; display: flex; align-items: center; gap: 15px;">
        <div style="width: 45px; height: 45px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" width="24" height="24"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <div>
            <div style="font-size: 0.85rem; font-weight: 700; color: #fff;">Schedule Exam</div>
            <div style="font-size: 0.72rem; color: rgba(255,255,255,0.6); margin-top: 2px;">Admin controls</div>
        </div>
    </div>
    <?php else: ?>
    <!-- Student sees Study Material Card -->
    <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; display: flex; align-items: center; gap: 15px;">
        <div style="width: 45px; height: 45px; background: #f0fdf4; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#013220" stroke-width="2" width="24" height="24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20M4 19.5A2.5 2.5 0 006.5 22H20M4 19.5V3a2.5 2.5 0 012.5-2.5H20v16.5"/></svg>
        </div>
        <div>
            <div style="font-size: 0.85rem; font-weight: 700; color: #013220;">Study Material</div>
            <div style="font-size: 0.72rem; color: #94a3b8; margin-top: 2px;">Download papers</div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px;">
    <!-- Recent Schedules -->
    <div class="card" style="border: 1px solid #e2e8f0; border-radius: 8px; background:#fff; overflow:hidden;">
        <div style="padding: 12px 15px; border-bottom: 1px solid #f1f5f9; background: #fafafa; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size: 0.8rem; font-weight: 700; color: #013220; margin: 0;">UPCOMING EXAMS</h3>
            <span onclick="openModal('examScheduleModal')" style="font-size:0.65rem; color:#50C878; font-weight:800; cursor:pointer; text-transform:uppercase;">View All</span>
        </div>
        <div style="padding:0;">
            <table style="width:100%; border-collapse:collapse; font-size:0.75rem;">
                <tbody>
                    <?php if(!empty($recent_schedules)): foreach($recent_schedules as $rs): ?>
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding:12px; color:#013220; font-weight:600;"><?= $rs['course_name'] ?></td>
                        <td style="padding:12px; color:#64748b;"><?= date('M d, Y', strtotime($rs['exam_date'])) ?></td>
                        <td style="padding:12px; text-align:right;"><span style="background:#f0fdf4; color:#166534; padding:2px 8px; border-radius:10px; font-weight:700; font-size:0.65rem;"><?= $rs['exam_type'] ?></span></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="3" style="padding:20px; text-align:center; color:#94a3b8;">No upcoming exams.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Results -->
    <div class="card" style="border: 1px solid #e2e8f0; border-radius: 8px; background:#fff; overflow:hidden;">
        <div style="padding: 12px 15px; border-bottom: 1px solid #f1f5f9; background: #fafafa; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size: 0.8rem; font-weight: 700; color: #013220; margin: 0;">RECENT RESULTS</h3>
            <span onclick="openModal('examResultsModal')" style="font-size:0.65rem; color:#50C878; font-weight:800; cursor:pointer; text-transform:uppercase;">View All</span>
        </div>
        <div style="padding:0;">
            <table style="width:100%; border-collapse:collapse; font-size:0.75rem;">
                <tbody>
                    <?php if(!empty($recent_results)): foreach($recent_results as $rr): ?>
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding:12px;">
                            <div style="color:#013220; font-weight:600;"><?= $rr['student_name'] ?></div>
                            <div style="font-size:0.6rem; color:#94a3b8;"><?= $rr['course_name'] ?></div>
                        </td>
                        <td style="padding:12px; text-align:right;">
                            <span style="font-weight:800; color:#013220;"><?= $rr['marks'] ?>/100</span>
                            <span style="margin-left:8px; background:#013220; color:#fff; padding:2px 6px; border-radius:4px; font-size:0.65rem;"><?= $rr['grade'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="2" style="padding:20px; text-align:center; color:#94a3b8;">No results recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     MODALS
═══════════════════════════════════════════════════════════ -->

<!-- 1. Upload Marks Modal -->
<?php if($role !== 'student'): ?>
<div id="uploadMarksModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:450px; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700;">Upload Marks</h3>
            <span onclick="closeModal('uploadMarksModal')" style="cursor:pointer; font-size:1.5rem;">&times;</span>
        </div>
        <form action="<?= base_url('exam/upload') ?>" method="POST" style="padding:20px;">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">SELECT STUDENT</label>
                <select name="student_id" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none; background:#fff;">
                    <?php if(!empty($students)): foreach($students as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['name'] ?> (ID: <?= $s['id'] ?>)</option>
                    <?php endforeach; else: ?>
                        <option value="">No students found</option>
                    <?php endif; ?>
                </select>
                <small style="display:block; font-size:0.65rem; color:#94a3b8; margin-top:4px;">Reference ID is shown in brackets.</small>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">COURSE</label>
                <select name="course_id" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none; background:#fff;">
                    <?php if(!empty($courses)): foreach($courses as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?> (ID: <?= $c['id'] ?>)</option>
                    <?php endforeach; else: ?>
                        <option value="">No courses available</option>
                    <?php endif; ?>
                </select>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">MARKS (MAX 100)</label>
                <input type="number" name="marks" min="0" max="100" required 
                       oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0;"
                       style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;" placeholder="0-100">
            </div>
            <button type="submit" style="width:100%; background:#013220; color:#fff; border:none; padding:12px; border-radius:60px; font-weight:800; cursor:pointer;">SAVE MARKS</button>
        </form>
    </div>
</div>

<!-- 2. Upload Paper Modal -->
<div id="uploadPaperModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:450px; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700;">Upload Question Paper</h3>
            <span onclick="closeModal('uploadPaperModal')" style="cursor:pointer; font-size:1.5rem;">&times;</span>
        </div>
        <form action="<?= base_url('exam/upload-paper') ?>" method="POST" style="padding:20px;">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">PAPER TITLE / CATEGORY</label>
                <select name="title" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none; background:#fff;">
                    <option value="Main Question Paper">Main Question Paper</option>
                    <option value="Supplementary Paper">Supplementary Paper</option>
                    <option value="Model Question Paper">Model Question Paper</option>
                    <option value="Reference Study Material">Reference Study Material</option>
                    <option value="Assignment Sheet">Assignment Sheet</option>
                    <option value="Laboratory Manual">Laboratory Manual</option>
                </select>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">COURSE</label>
                <select name="course_id" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none; background:#fff;">
                    <?php if(!empty($courses)): foreach($courses as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?> (ID: <?= $c['id'] ?>)</option>
                    <?php endforeach; else: ?>
                        <option value="">No courses available</option>
                    <?php endif; ?>
                </select>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">EXAM TYPE</label>
                <select name="exam_type" style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                    <option value="Midterm">Midterm</option>
                    <option value="Final">Final</option>
                </select>
            </div>
            <button type="submit" style="width:100%; background:#013220; color:#fff; border:none; padding:12px; border-radius:60px; font-weight:800; cursor:pointer;">UPLOAD PAPER</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- 3. Schedule Exam Modal (Admin only) -->
<?php if($role === 'admin'): ?>
<div id="scheduleExamModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:450px; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700;">Schedule New Exam</h3>
            <span onclick="closeModal('scheduleExamModal')" style="cursor:pointer; font-size:1.5rem;">&times;</span>
        </div>
        <form action="<?= base_url('exam/schedule-exam') ?>" method="POST" style="padding:20px;">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">COURSE</label>
                <select name="course_id" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none; background:#fff;">
                    <?php if(!empty($courses)): foreach($courses as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?> (ID: <?= $c['id'] ?>)</option>
                    <?php endforeach; else: ?>
                        <option value="">No courses available</option>
                    <?php endif; ?>
                </select>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">EXAM DATE</label>
                <input type="date" name="exam_date" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">EXAM TYPE</label>
                <select name="exam_type" style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                    <option value="Midterm">Midterm</option>
                    <option value="Final">Final</option>
                </select>
            </div>
            <button type="submit" style="width:100%; background:#013220; color:#fff; border:none; padding:12px; border-radius:60px; font-weight:800; cursor:pointer;">CREATE SCHEDULE</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- 4. Exam Schedule View Modal -->
<div id="examScheduleModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:650px; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700;">Full Examination Timetable</h3>
            <span onclick="closeModal('examScheduleModal')" style="cursor:pointer; font-size:1.5rem;">&times;</span>
        </div>
        <div style="padding:20px; max-height:400px; overflow-y:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                        <th style="padding:12px; text-align:left; font-size:0.75rem; color:#718096;">DATE</th>
                        <th style="padding:12px; text-align:left; font-size:0.75rem; color:#718096;">COURSE</th>
                        <th style="padding:12px; text-align:center; font-size:0.75rem; color:#718096;">TYPE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($recent_schedules)): foreach($recent_schedules as $rs): ?>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:12px; font-size:0.8rem; font-weight:700; color:#013220;"><?= date('D, M d, Y', strtotime($rs['exam_date'])) ?></td>
                        <td style="padding:12px; font-size:0.8rem; color:#4a5568;"><?= $rs['course_name'] ?></td>
                        <td style="padding:12px; text-align:center;"><span style="background:#f0fdf4; color:#166534; padding:3px 10px; border-radius:20px; font-size:0.7rem; font-weight:700;"><?= $rs['exam_type'] ?></span></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="3" style="text-align:center; padding:30px; color:#a0aec0;">No exams scheduled.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 5. Exam Results View Modal -->
<div id="examResultsModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3); backdrop-filter:blur(6px); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:800px; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700;">Examination Performance Records</h3>
            <span onclick="closeModal('examResultsModal')" style="cursor:pointer; font-size:1.5rem;">&times;</span>
        </div>
        <div style="padding:20px; max-height:500px; overflow-y:auto;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                        <th style="padding:12px; text-align:left; font-size:0.75rem; color:#718096;">STUDENT</th>
                        <th style="padding:12px; text-align:left; font-size:0.75rem; color:#718096;">COURSE</th>
                        <th style="padding:12px; text-align:center; font-size:0.75rem; color:#718096;">MARKS</th>
                        <th style="padding:12px; text-align:center; font-size:0.75rem; color:#718096;">GRADE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($recent_results)): foreach($recent_results as $rr): ?>
                    <tr style="border-bottom:1px solid #f1f5f9;">
                        <td style="padding:12px; font-size:0.8rem; font-weight:700; color:#013220;"><?= $rr['student_name'] ?></td>
                        <td style="padding:12px; font-size:0.8rem; color:#4a5568;"><?= $rr['course_name'] ?></td>
                        <td style="padding:12px; text-align:center; font-size:0.85rem; font-weight:800; color:#013220;"><?= $rr['marks'] ?> / 100</td>
                        <td style="padding:12px; text-align:center;"><span style="background:#013220; color:#fff; padding:4px 10px; border-radius:4px; font-size:0.75rem; font-weight:800;"><?= $rr['grade'] ?></span></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" style="text-align:center; padding:30px; color:#a0aec0;">No results found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }
</script>

<?php $this->endSection() ?>
