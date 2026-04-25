<?php
$mysqli = new mysqli("localhost", "root", "", "libmangt");
$result = $mysqli->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    echo $row[0] . "\n";
}
