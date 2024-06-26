
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM notices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = "Notice deleted successfully";
    } else {
        $error = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

header("Location: manage_notices.php?message=" . urlencode($message) . "&error=" . urlencode($error));
exit();
?>