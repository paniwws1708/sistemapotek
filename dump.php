<?php
$conn = mysqli_connect("localhost", "root", "", "sistemapotek_db", 3307);
if (!$conn) die("Failed");

$result = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_row($result)) {
    echo "TABLE: " . $row[0] . "\n";
    $cols = mysqli_query($conn, "DESCRIBE " . $row[0]);
    while ($col = mysqli_fetch_assoc($cols)) {
        echo "  " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
}
?>
