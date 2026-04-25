<?php $this->extend('layouts/main') ?>

<?php $this->section('content') ?>

<?php $role = session('user_role'); ?>

<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
    <h2 style="font-size: 1.1rem; color: #013220; font-weight: 800; letter-spacing: 0.5px;">
        <?= $role === 'student' ? 'My Examination Results' : 'All Examination Results' ?>
    </h2>
    <div style="display:flex; gap:10px; align-items:center;">
        <?php if($role === 'admin'): ?>
        <button onclick="window.location.href='<?= base_url('exam/publish') ?>'" style="background: #013220; color: #fff; border: none; font-size: 0.75rem; border-radius: 4px; padding: 8px 16px; font-weight: 700; cursor: pointer;">PUBLISH ALL RESULTS</button>
        <?php endif; ?>
        <a href="<?= base_url('exam') ?>" style="font-size: 0.75rem; color: #013220; font-weight: 700; text-decoration: none;">← Back to Exam</a>
    </div>
</div>

<?php if(!empty($results)): ?>

<?php if($role === 'student'): ?>
<!-- Student CGPA Summary Card -->
<?php
    $totalMarks = array_sum(array_column($results, 'marks'));
    $avgMarks   = count($results) > 0 ? round($totalMarks / count($results), 2) : 0;
    $cgpa       = round($avgMarks / 25, 2); // 100 marks = 4.0 CGPA scale
?>
<div style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
    <!-- Subjects -->
    <div style="background: #fff; border: 1px solid #e2e8f0; padding: 12px 18px; border-radius: 10px; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="width: 38px; height: 38px; background: #f0fdf4; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#013220" stroke-width="2" width="20" height="20"><path d="M4 19.5A2.5 2.5 0 016.5 17H20M4 19.5A2.5 2.5 0 006.5 22H20M4 19.5V3a2.5 2.5 0 012.5-2.5H20v16.5"/></svg>
        </div>
        <div>
            <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Subjects</div>
            <div style="font-size: 1.1rem; font-weight: 900; color: #013220;"><?= count($results) ?></div>
        </div>
    </div>

    <!-- Avg Marks -->
    <div style="background: #fff; border: 1px solid #e2e8f0; padding: 12px 18px; border-radius: 10px; display: flex; align-items: center; gap: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div style="width: 38px; height: 38px; background: #fff7ed; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" width="20" height="20"><path d="M12 20v-6M6 20V10M18 20V4"/></svg>
        </div>
        <div>
            <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Avg Marks</div>
            <div style="font-size: 1.1rem; font-weight: 900; color: #013220;"><?= $avgMarks ?>%</div>
        </div>
    </div>

    <!-- CGPA -->
    <div style="background: #013220; padding: 12px 18px; border-radius: 10px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 10px rgba(1,50,32,0.2);">
        <div style="width: 38px; height: 38px; background: rgba(255,255,255,0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" width="20" height="20"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422A12.083 12.083 0 0119.82 18a11.952 11.952 0 01-7.82 2.055A11.952 11.952 0 014.18 18a12.083 12.083 0 011.66-7.422L12 14z"/></svg>
        </div>
        <div>
            <div style="font-size: 0.65rem; color: rgba(255,255,255,0.6); font-weight: 700; text-transform: uppercase;">Total CGPA</div>
            <div style="font-size: 1.1rem; font-weight: 900; color: #fff;"><?= $cgpa ?> / 4.0</div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Results Table -->
<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff;">
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #edf2f7;">
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096; width: 60px;">ID</th>
                    <?php if($role !== 'student'): ?>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096;">STUDENT</th>
                    <?php endif; ?>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096;">COURSE ID</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096; width: 120px;">MARKS</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096; width: 100px;">GRADE</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096; width: 120px;">STATUS</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096; width: 120px;">PERFORMANCE</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($results as $r): ?>
                <?php
                    $marks = $r['marks'];
                    $perf  = $marks >= 90 ? ['O', '#166534', '#dcfce7']
                           : ($marks >= 75 ? ['A+', '#1e40af', '#dbeafe']
                           : ($marks >= 60 ? ['A', '#0369a1', '#e0f2fe']
                           : ($marks >= 50 ? ['B', '#92400e', '#fef3c7']
                           : ['F', '#b91c1c', '#fee2e2'])));
                ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;">#<?= $r['id'] ?></td>
                    <?php if($role !== 'student'): ?>
                    <td style="padding: 12px; font-size: 0.8rem; font-weight: 600; color: #013220;"><?= $r['student_name'] ?? '—' ?></td>
                    <?php endif; ?>
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;">Course #<?= $r['course_id'] ?></td>
                    <td style="padding: 12px; text-align: center;">
                        <div style="position: relative; height: 6px; background: #f1f5f9; border-radius: 3px; margin-bottom: 4px;">
                            <div style="position: absolute; left:0; top:0; height:100%; width:<?= $marks ?>%; background:#013220; border-radius:3px;"></div>
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #013220;"><?= $marks ?>/100</span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <span style="background: <?= $perf[2] ?>; color: <?= $perf[1] ?>; padding: 3px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            <?= $r['grade'] ?: $perf[0] ?>
                        </span>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <?php if($r['is_published']): ?>
                        <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 700;">PUBLISHED</span>
                        <?php else: ?>
                        <span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 700;">DRAFT</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px; text-align: center;">
                        <div style="font-size: 0.62rem; font-weight: 800; color: <?= $perf[1] ?>; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.3px;">
                            <?= $marks >= 90 ? 'Excellent' : ($marks >= 75 ? 'Very Good' : ($marks >= 60 ? 'Good' : ($marks >= 40 ? 'Satisfactory' : 'Needs Improvement'))) ?>
                        </div>
                        <div style="width: 80px; height: 4px; background: #f1f5f9; border-radius: 10px; margin: 0 auto; overflow: hidden; display: flex;">
                            <div style="width: <?= $marks ?>%; height: 100%; background: <?= $perf[1] ?>; border-radius: 10px;"></div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php else: ?>
<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff; padding: 40px; text-align: center;">
    <svg viewBox="0 0 24 24" fill="none" stroke="#013220" stroke-width="1.5" width="48" height="48" style="opacity: 0.2; margin-bottom: 10px;">
        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
    </svg>
    <p style="color: #64748b; font-size: 0.9rem; margin: 0;">
        <?= $role === 'student' ? 'No results found for your account yet.' : 'No examination results found.' ?>
    </p>
</div>
<?php endif; ?>

<?php $this->endSection() ?>
