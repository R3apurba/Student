<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = "Student deleted successfully";
    } else {
        $error = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

header("Location: admin_dashboard.php?message=" . urlencode($message) . "&error=" . urlencode($error));
exit();
?>