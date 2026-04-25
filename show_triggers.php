<?php
$mysqli = new mysqli("localhost", "root", "", "libmangt");
$result = $mysqli->query("SHOW TRIGGERS");
while ($row = $result->fetch_assoc()) {
    echo $row['Trigger'] . "\n";
}
