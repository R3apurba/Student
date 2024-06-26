<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    $sql = "SELECT password FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['student_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    
    if ($student['password'] === $current_password) {
        if ($new_password === $confirm_password) {
            $sql = "UPDATE students SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password, $_SESSION['student_id']);
            
            if ($stmt->execute()) {
                $success = "Password changed successfully. You will be redirected to the dashboard in 3 seconds.";
                header("refresh:3;url=student_dashboard.php");
            } else {
                $error = "An error occurred. Please try again.";
            }
        } else {
            $error = "New passwords do not match.";
        }
    } else {
        $error = "Current password is incorrect.";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Change Password</h1>
        
        <?php if ($error): ?>
            <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <p class="text-green-600 mb-4"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="current_password" class="block text-gray-700 text-sm font-bold mb-2">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="new_password" class="block text-gray-700 text-sm font-bold mb-2">New Password:</label>
                <input type="password" id="new_password" name="new_password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="flex items-center justify-left ">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
        Change Password
    </button>
    <a href="student_dashboard.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Cancel
    </a>
</div>

        </form>
    </div>
</body>
</html>