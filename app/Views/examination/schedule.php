<?php $this->extend('layouts/main') ?>

<?php $this->section('content') ?>

<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
    <h2 style="font-size: 1.1rem; color: #013220; font-weight: 800; letter-spacing: 0.5px;">Exam Schedule</h2>
    <a href="<?= base_url('exam') ?>" style="font-size: 0.75rem; color: #013220; font-weight: 700; text-decoration: none;">← Back to Exam</a>
</div>

<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff;">
    <div class="table-container">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #edf2f7;">
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096; width: 60px;">ID</th>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096;">COURSE</th>
                    <th style="padding: 12px; text-align: left; font-size: 0.75rem; color: #718096; width: 150px;">EXAM DATE</th>
                    <th style="padding: 12px; text-align: center; font-size: 0.75rem; color: #718096; width: 130px;">TYPE</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($schedules)): foreach($schedules as $s): ?>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;">#<?= $s['id'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; font-weight: 600; color: #013220;"><?= $s['course_name'] ?? 'Course #'.$s['course_id'] ?></td>
                    <td style="padding: 12px; font-size: 0.8rem; color: #64748b;"><?= date('d M Y', strtotime($s['exam_date'])) ?></td>
                    <td style="padding: 12px; text-align: center;">
                        <span style="background: <?= $s['exam_type'] === 'Final' ? '#fee2e2' : '#fef3c7' ?>; color: <?= $s['exam_type'] === 'Final' ? '#b91c1c' : '#92400e' ?>; padding: 3px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">
                            <?= $s['exam_type'] ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" style="text-align:center; padding: 30px; color: #a0aec0; font-size: 0.8rem;">No exam schedule found for this semester.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->endSection() ?>
