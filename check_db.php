<?php
$mysqli = new mysqli("localhost", "root", "", "libmangt");
$result = $mysqli->query("SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
$result = $mysqli->query("SELECT * FROM students");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
$result = $mysqli->query("SELECT * FROM mentor_allocation");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
