<?php $this->extend('layouts/main') ?>

<?php $this->section('content') ?>

<style>
    .issue-row { transition: background 0.2s; border-left: 3px solid transparent; }
    .issue-row:hover { background: #fdfdfd !important; }
    .issue-row.active { background: #f8fafc; border-left: 3px solid #013220; }
    .details-row { display: none; background: #fcfcfc; }
    .details-wrapper { padding: 20px 40px; display: flex; gap: 30px; border-bottom: 1px solid #edf2f7; }
    .detail-field { flex: 1; }
    .detail-label { display: block; font-size: 0.65rem; font-weight: 800; color: #718096; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
    .detail-value { width: 100%; padding: 8px 12px; background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; font-size: 0.75rem; color: #1a202c; font-weight: 500; }
    .arrow-icon { transition: transform 0.3s; color: #cbd5e0; }
    .arrow-active { transform: rotate(90deg); color: #013220; }
</style>

<?php
$role = session('user_role');
?>

<div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
    <h2 style="font-size: 1.1rem; color: #013220; font-weight: 800; letter-spacing: 0.5px;">Library Inventory Management</h2>
    <?php if($role !== 'student'): ?>
    <button class="btn btn-primary" onclick="openModal('newBookModal')" style="background: #013220; color: #fff; font-size: 0.75rem; border-radius: 4px; padding: 8px 20px; font-weight: 700; cursor: pointer;">NEW RECORD</button>
    <?php endif; ?>
</div>

<!-- Flash messages handled by global Toast system -->

<!-- Statistical Badges Row -->
<div class="stat-badges-row" style="margin-bottom: 15px;">
    <div class="stat-badge" onclick="filterTab('all')" id="tab-all">All <span class="count" style="background:#013220;"><?= $total_books ?? 0 ?></span></div>
    <div class="stat-badge active" onclick="filterTab('books')" id="tab-books" style="border-color: #50C878;">Books <span class="count" style="background:#013220;"><?= $total_books ?? 0 ?></span></div>
    <div class="stat-badge" onclick="filterTab('reference')" id="tab-reference">Reference <span class="count" style="background:#50C878;"><?= $reference_books ?? 0 ?></span></div>
    <div class="stat-badge" onclick="filterTab('available')" id="tab-available">Available <span class="count" style="background:#50C878;"><?= $available_books ?? 0 ?></span></div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     BOOK INVENTORY TABLE
═══════════════════════════════════════════════════════════ -->
<div class="card" style="border: 1px solid #e2e8f0; border-radius: 4px; box-shadow: none; background:#fff; margin-bottom: 20px;">
    <div style="padding: 10px 15px; background: #fafafa; border-bottom: 1px solid #f1f5f9;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #013220; margin: 0;">BOOK INVENTORY</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 15px; background: #fff; border-bottom: 1px solid #f1f5f9;">
            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #718096;">
                <select style="padding: 3px 8px; border-radius: 4px; border: 1px solid #e2e8f0; background: #fff; font-size: 0.8rem;">
                    <option>10</option>
                </select>
                records
            </div>
            <div style="position: relative;">
                <input type="text" id="bookSearch" placeholder="Search books..." onkeyup="filterBooks()" style="padding: 4px 12px 4px 30px; border-radius: 4px; width: 200px; border: 1px solid #e2e8f0; font-size: 0.75rem;">
                <svg viewBox="0 0 24 24" fill="none" stroke="#a0aec0" stroke-width="2" width="14" height="14" style="position: absolute; left: 10px; top: 7px;"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            </div>
        </div>

        <div class="table-container">
            <table id="booksTable" style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th class="col-id">ID</th>
                        <th style="width: 150px;">ISBN</th>
                        <th style="width: 260px;">Title</th>
                        <th style="width: 120px;">Category</th>
                        <th style="width: 160px;">Author</th>
                        <th style="width: 60px; text-align: center;">Qty</th>
                        <th style="width: 100px;">Status</th>
                        <th class="col-action" style="width: 120px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($books)): foreach($books as $book): ?>
                    <tr class="issue-row" id="book-row-<?= $book['id'] ?>" onclick="toggleBookDetails(<?= $book['id'] ?>)">
                        <td style="text-align: center;">
                            <svg id="book-arrow-<?= $book['id'] ?>" class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" width="14" height="14">
                                <path d="M9 5l7 7-7 7"/>
                            </svg>
                        </td>
                        <td class="col-id"><?= $book['id'] ?></td>
                        <td><?= $book['isbn'] ?></td>
                        <td style="font-weight: 600; color: #013220;"><?= $book['title'] ?></td>
                        <td style="color: #64748b;"><?= $book['category'] ?></td>
                        <td><?= $book['author'] ?></td>
                        <td style="font-weight: 500; text-align: center;"><?= $book['copies_available'] ?></td>
                        <td><span class="status-pill <?= $book['copies_available'] > 0 ? 'status-active' : 'status-inactive' ?>"><?= $book['copies_available'] > 0 ? 'AVAILABLE' : 'OUT' ?></span></td>
                        <td class="col-action">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <?php if($role === 'student'): ?>
                                    <?php 
                                    $activeIssue = null;
                                    foreach($book['issues'] as $is) {
                                        if($is['status'] === 'Issued') { $activeIssue = $is; break; }
                                    }
                                    ?>
                                    <?php if($activeIssue): ?>
                                        <!-- Student: Return Button (Primary Action if already borrowed) -->
                                        <button onclick="event.stopPropagation(); openConfirmModal('Return Book', 'Return &quot;<?= addslashes($book['title']) ?>&quot;?', function(){ window.location.href='<?= base_url('library/return/'.$activeIssue['id']) ?>'; })"
                                                style="background: #e53e3e; color: #fff; border: none; padding: 5px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; cursor: pointer; white-space: nowrap;">
                                            ↩️ RETURN
                                        </button>
                                    <?php elseif(strpos($book['category'], 'Reference') !== false): ?>
                                        <span style="color: #64748b; font-size: 0.65rem; font-weight: 700; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; border: 1px dashed #cbd5e0;">🏛️ REF ONLY</span>
                                    <?php elseif($book['copies_available'] > 0): ?>
                                        <button onclick="event.stopPropagation(); openConfirmModal('Borrow Book', 'Borrow &quot;<?= addslashes($book['title']) ?>&quot;? It will be due in 14 days.', function(){ borrowBook(<?= $book['id'] ?>); })"
                                                style="background: #013220; color: #fff; border: none; padding: 5px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; cursor: pointer; white-space: nowrap;">
                                            📚 BORROW
                                        </button>
                                    <?php else: ?>
                                        <span style="color: #94a3b8; font-size: 0.7rem; font-style: italic;">Out of Stock</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- Admin/Faculty Actions -->
                                    <div style="display: flex; gap: 4px;">
                                        <div style="width: 26px; height: 26px; background: <?= strpos($book['category'], 'Reference') !== false ? '#f59e0b' : '#99a9bf' ?>; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer;" 
                                             onclick="event.stopPropagation(); window.location.href='<?= base_url('library/recommend/'.$book['id']) ?>'"
                                             title="<?= strpos($book['category'], 'Reference') !== false ? 'Remove from Reference' : 'Recommend as Reference' ?>">
                                            <svg viewBox="0 0 24 24" fill="<?= strpos($book['category'], 'Reference') !== false ? '#fff' : 'none' ?>" stroke="#fff" stroke-width="2" width="14" height="14">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                            </svg>
                                        </div>
                                        <div style="width: 26px; height: 26px; background: #99a9bf; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer;" title="Edit"
                                             onclick="event.stopPropagation(); openEditModal(<?= htmlspecialchars(json_encode($book)) ?>)">
                                             <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" width="14" height="14" style="transform: rotate(-45deg);"><rect x="2" y="9" width="20" height="6" rx="3" /></svg>
                                         </div>
                                        <div style="width: 26px; height: 26px; background: #ff6b6b; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                                             onclick="event.stopPropagation(); openConfirmModal('Delete Book', 'Permanently remove &quot;<?= addslashes($book['title']) ?>&quot; from the library?', function(){ window.location.href='<?= base_url('library/delete/'.$book['id']) ?>'; })"
                                             title="Delete">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" width="14" height="14">
                                                <path d="M5 10c0-1.1.9-2 2-2h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V10z" />
                                                <path d="M3 6h18m-14 0v-1a2 2 0 012-2h6a2 2 0 012 2v1" />
                                            </svg>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <!-- Nested Table: Issue History -->
                    <tr id="book-details-<?= $book['id'] ?>" class="details-row">
                        <td colspan="9" style="padding: 0;">
                            <div style="padding: 15px 30px; background: #fdfdfd; border-bottom: 1px solid #edf2f7;">
                                <h4 style="font-size: 0.75rem; color: #013220; font-weight: 800; margin-bottom: 10px; display: flex; align-items: center; gap: 8px;">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9"/></svg>
                                    ISSUE HISTORY & LOG
                                </h4>
                                <table style="width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #e2e8f0; border-radius: 4px; overflow: hidden;">
                                    <thead>
                                        <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                            <th style="padding: 8px 12px; text-align: left; font-size: 0.65rem; color: #718096; text-transform: uppercase;">ID</th>
                                            <?php if($role !== 'student'): ?>
                                            <th style="padding: 8px 12px; text-align: left; font-size: 0.65rem; color: #718096; text-transform: uppercase;">Student Name</th>
                                            <?php endif; ?>
                                            <th style="padding: 8px 12px; text-align: left; font-size: 0.65rem; color: #718096; text-transform: uppercase;">Issue Date</th>
                                            <th style="padding: 8px 12px; text-align: left; font-size: 0.65rem; color: #718096; text-transform: uppercase;">Due Date</th>
                                            <th style="padding: 8px 12px; text-align: center; font-size: 0.65rem; color: #718096; text-transform: uppercase;">Time Left</th>
                                            <th style="padding: 8px 12px; text-align: center; font-size: 0.65rem; color: #718096; text-transform: uppercase;">Status</th>
                                            <?php if($role === 'student'): ?>
                                            <th style="padding: 8px 12px; text-align: center; font-size: 0.65rem; color: #718096; text-transform: uppercase;">Action</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($book['issues'])): foreach($book['issues'] as $issue): ?>
                                        <tr style="border-bottom: 1px solid #f1f5f9;">
                                            <td style="padding: 8px 12px; font-size: 0.75rem; color: #64748b;">#<?= $issue['id'] ?></td>
                                            <?php if($role !== 'student'): ?>
                                            <td style="padding: 8px 12px; font-size: 0.75rem; font-weight: 600; color: #013220;"><?= $issue['student_name'] ?? '—' ?></td>
                                            <?php endif; ?>
                                            <td style="padding: 8px 12px; font-size: 0.75rem; color: #64748b;"><?= date('d M Y', strtotime($issue['issue_date'])) ?></td>
                                            <td style="padding: 8px 12px; font-size: 0.75rem; color: <?= strtotime($issue['return_date']) < time() && $issue['status'] === 'Issued' ? '#e53e3e' : '#64748b' ?>; font-weight: <?= strtotime($issue['return_date']) < time() && $issue['status'] === 'Issued' ? '700' : '400' ?>;">
                                                <?= date('d M Y', strtotime($issue['return_date'])) ?>
                                                <?php if(strtotime($issue['return_date']) < time() && $issue['status'] === 'Issued'): ?>
                                                <span style="font-size:0.6rem; background:#fee2e2; color:#b91c1c; padding:1px 4px; border-radius:3px;">OVERDUE</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="padding: 8px 12px; text-align: center; font-size: 0.75rem;">
                                                <?php 
                                                if($issue['status'] === 'Issued') {
                                                    $diff = strtotime($issue['return_date']) - time();
                                                    $days = ceil($diff / (60 * 60 * 24));
                                                    if($days > 0) {
                                                        echo "<span style='color: #2f855a; font-weight: 700;'>$days Days left</span>";
                                                    } else {
                                                        echo "<span style='color: #e53e3e; font-weight: 700;'>EXPIRED</span>";
                                                    }
                                                } else {
                                                    echo "<span style='color: #a0aec0;'>—</span>";
                                                }
                                                ?>
                                            </td>
                                            <td style="padding: 8px 12px; text-align: center;">
                                                <span style="background: <?= $issue['status'] === 'Issued' ? '#dcfce7' : '#f1f5f9' ?>; color: <?= $issue['status'] === 'Issued' ? '#166534' : '#64748b' ?>; padding: 2px 8px; border-radius: 12px; font-size: 0.65rem; font-weight: 700;">
                                                    <?= strtoupper($issue['status']) ?>
                                                </span>
                                            </td>
                                            <?php if($role === 'student'): ?>
                                            <td style="padding: 8px 12px; text-align: center;">
                                                <?php if($issue['status'] === 'Issued'): ?>
                                                <button onclick="openConfirmModal('Return Book', 'Return &quot;<?= addslashes($book['title']) ?>&quot;?', function(){ window.location.href='<?= base_url('library/return/'.$issue['id']) ?>'; })"
                                                        style="background: #e53e3e; color: #fff; border: none; padding: 4px 10px; border-radius: 15px; font-size: 0.65rem; font-weight: 700; cursor: pointer;">
                                                    RETURN
                                                </button>
                                                <?php else: ?>
                                                <span style="color:#94a3b8; font-size:0.65rem;">Returned</span>
                                                <?php endif; ?>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php endforeach; else: ?>
                                        <tr><td colspan="<?= $role === 'student' ? 5 : 5 ?>" style="text-align:center; padding: 15px; color: #a0aec0; font-size: 0.75rem;">No issue history found for this book.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="9" style="text-align:center; padding: 20px; color: #a0aec0;">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- REDUNDANT SECTION REMOVED: Issue log is now integrated into the book inventory table -->

<!-- Hidden borrow form (submitted via JS) -->
<form id="borrowForm" action="<?= base_url('library/issue') ?>" method="POST" style="display:none;">
    <input type="hidden" name="book_id" id="borrowBookId">
</form>

<!-- Admin/Faculty: Add New Book Modal -->
<?php if($role !== 'student'): ?>
<div id="newBookModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:450px; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700;">Add New Book</h3>
            <span onclick="closeModal('newBookModal')" style="cursor:pointer; font-size:1.5rem;">&times;</span>
        </div>
        <form action="<?= base_url('library/create') ?>" method="POST" style="padding:20px;">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">TITLE</label>
                <input type="text" name="title" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">AUTHOR</label>
                <input type="text" name="author" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="display:flex; gap:15px; margin-bottom:15px;">
                <div style="flex:1;">
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">ISBN</label>
                    <input type="text" name="isbn" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                </div>
                <div style="flex:1;">
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">COPIES</label>
                    <input type="number" name="copies_available" value="1" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">CATEGORY</label>
                <select name="category" style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                    <option>Programming 💻</option>
                    <option>Reference 📚</option>
                    <option>Fiction 📖</option>
                    <option>Non-Fiction 📘</option>
                    <option>Science 🔬</option>
                    <option>History 🏛️</option>
                    <option>Biography 👤</option>
                    <option>Business 💼</option>
                </select>
            </div>
            <button type="submit" style="width:100%; background:#013220; color:#fff; border:none; padding:12px; border-radius:60px; font-weight:800; cursor:pointer;">SAVE BOOK</button>
        </form>
    </div>
</div>
<?php endif; ?>

<!-- Admin/Faculty: Edit Book Modal -->
<?php if($role !== 'student'): ?>
<div id="editBookModal" class="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; width:450px; border-radius:12px; overflow:hidden; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
        <div style="background:#013220; color:#fff; padding:15px 20px; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="font-size:1rem; font-weight:700;">Edit Book Record</h3>
            <span onclick="closeModal('editBookModal')" style="cursor:pointer; font-size:1.5rem;">&times;</span>
        </div>
        <form id="editBookForm" action="" method="POST" style="padding:20px;">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">TITLE</label>
                <input type="text" name="title" id="edit_title" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">AUTHOR</label>
                <input type="text" name="author" id="edit_author" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
            </div>
            <div style="display:flex; gap:15px; margin-bottom:15px;">
                <div style="flex:1;">
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">ISBN</label>
                    <input type="text" name="isbn" id="edit_isbn" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                </div>
                <div style="flex:1;">
                    <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">COPIES</label>
                    <input type="number" name="copies_available" id="edit_copies" required style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:0.75rem; font-weight:800; color:#013220; margin-bottom:5px;">CATEGORY</label>
                <select name="category" id="edit_category" style="width:100%; padding:10px; border:1px solid #e2e8f0; border-radius:6px; outline:none;">
                    <option>Programming 💻</option>
                    <option>Reference 📚</option>
                    <option>General 📚</option>
                    <option>Fiction 📖</option>
                    <option>Non-Fiction 📘</option>
                    <option>Science 🔬</option>
                    <option>History 🏛️</option>
                    <option>Biography 👤</option>
                    <option>Business 💼</option>
                </select>
            </div>
            <button type="submit" style="width:100%; background:#013220; color:#fff; border:none; padding:12px; border-radius:60px; font-weight:800; cursor:pointer;">UPDATE BOOK</button>
        </form>
    </div>
</div>
<?php endif; ?>

<script>
function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

function openEditModal(book) {
    document.getElementById('edit_title').value = book.title;
    document.getElementById('edit_author').value = book.author;
    document.getElementById('edit_isbn').value = book.isbn;
    document.getElementById('edit_copies').value = book.copies_available;
    document.getElementById('edit_category').value = book.category;
    document.getElementById('editBookForm').action = '<?= base_url('library/update') ?>/' + book.id;
    openModal('editBookModal');
}

function borrowBook(bookId) {
    document.getElementById('borrowBookId').value = bookId;
    document.getElementById('borrowForm').submit();
}

function filterBooks() {
    var input = document.getElementById('bookSearch').value.toLowerCase();
    var rows  = document.querySelectorAll('#booksTable tbody tr.issue-row');
    rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        var detailsId = row.id.replace('book-row-', 'book-details-');
        var detailsRow = document.getElementById(detailsId);
        
        if (text.includes(input)) {
            row.style.display = '';
            // Details row remains hidden unless expanded, but we don't force 'none' here
        } else {
            row.style.display = 'none';
            if (detailsRow) detailsRow.style.display = 'none';
        }
    });
}

function filterTab(type) {
    // Update tab UI
    document.querySelectorAll('.stat-badge').forEach(el => {
        el.classList.remove('active');
        el.style.borderColor = '#e2e8f0';
    });
    document.getElementById('tab-' + type).classList.add('active');
    document.getElementById('tab-' + type).style.borderColor = '#50C878';

    var rows = document.querySelectorAll('#booksTable tbody tr.issue-row');
    rows.forEach(function(row) {
        var category = row.querySelector('td:nth-child(5)').textContent; // Category column
        var status   = row.querySelector('td:nth-child(8)').textContent; // Status column
        var show     = false;

        if (type === 'all' || type === 'books') {
            show = true;
        } else if (type === 'reference') {
            show = category.includes('Reference');
        } else if (type === 'available') {
            show = status.includes('AVAILABLE');
        }

        row.style.display = show ? '' : 'none';
        
        // Always hide details row when filtering
        var detailsId = row.id.replace('book-row-', 'book-details-');
        var detailsRow = document.getElementById(detailsId);
        if (detailsRow) detailsRow.style.display = 'none';
    });
}

function toggleBookDetails(id) {
    var detailsRow = document.getElementById('book-details-' + id);
    var mainRow    = document.getElementById('book-row-' + id);
    var arrow      = document.getElementById('book-arrow-' + id);
    
    if (detailsRow.style.display === 'table-row') {
        detailsRow.style.display = 'none';
        mainRow.classList.remove('active');
        arrow.classList.remove('arrow-active');
    } else {
        // Close others for cleaner UI
        document.querySelectorAll('.details-row').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.issue-row').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.arrow-icon').forEach(el => el.classList.remove('arrow-active'));

        detailsRow.style.display = 'table-row';
        mainRow.classList.add('active');
        arrow.classList.add('arrow-active');
    }
}
</script>

<?php $this->endSection() ?>
