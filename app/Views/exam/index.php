<?php $this->extend('layouts/main') ?>

<?php $this->section('content') ?>
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="font-size: 1.2rem; color: #4b2a4a; font-weight: 700;">Examination Management Dashboard</h2>
    <button class="btn btn-primary" style="background: #4b2a4a; font-size: 0.8rem; border-radius: 20px; padding: 6px 20px;">Publish Results</button>
</div>

<div class="stat-badges-row">
    <div class="stat-badge">Total Exams <span class="count">24</span></div>
    <div class="stat-badge active">Results Ready <span class="count">18</span></div>
    <div class="stat-badge">Pending Upload <span class="count">6</span></div>
</div>

<div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
    <div class="card-body" style="padding: 15px;">
        <div class="table-container">
            <table style="border: 1px solid #eee;">
                <thead>
                    <tr>
                        <th>Exam ID</th>
                        <th>Course</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th style="text-align: center;">Action</th>
                    </tr>
                    <tr class="search-header">
                        <td><input type="text" placeholder="Sea"></td>
                        <td><input type="text" placeholder="Search Course"></td>
                        <td><input type="text" placeholder="Search Date"></td>
                        <td><input type="text" placeholder="Search Type"></td>
                        <td><input type="text" placeholder="Search Status"></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="color: #a0aec0;">889</td>
                        <td>CS-101 Data Structures</td>
                        <td>2026-05-12</td>
                        <td>Midterm</td>
                        <td><span class="status-pill status-active">Active</span></td>
                        <td class="action-btns" style="text-align: center;">
                            <i class="fa-solid fa-pen-to-square"></i>
                            <i class="fa-solid fa-link"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->endSection() ?>
