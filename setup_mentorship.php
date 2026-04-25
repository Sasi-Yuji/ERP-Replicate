<?php
$mysqli = new mysqli("localhost", "root", "", "libmangt");

// Create mentor_allocation table
$mysqli->query("CREATE TABLE IF NOT EXISTS mentor_allocation (
    student_id INT UNSIGNED PRIMARY KEY,
    mentor_id INT UNSIGNED DEFAULT 1,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB");

// Seed mentor_allocation
// Assign student 2 to faculty 2
$mysqli->query("REPLACE INTO mentor_allocation (student_id, mentor_id) VALUES (2, 2)");
// Assign student 3 to faculty 1 (admin)
$mysqli->query("REPLACE INTO mentor_allocation (student_id, mentor_id) VALUES (3, 1)");

echo "DB Setup complete.\n";
