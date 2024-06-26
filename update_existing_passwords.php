<?php
session_start();
// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    die("Access denied. Admin login required.");
}

include 'db_connection.php';

$sql = "UPDATE students SET password = REPLACE(dob, '-', '') WHERE password IS NULL OR password = ''";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    echo "Passwords updated successfully for students without passwords.";
} else {
    echo "Error updating passwords: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>