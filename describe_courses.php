<?php
$mysqli = new mysqli("localhost", "root", "", "libmangt");
$table = 'courses';
echo "--- $table ---\n";
$result = $mysqli->query("DESCRIBE $table");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
