<?php
session_start();
include 'db_connection.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = $_POST['uid'];
    $password = $_POST['password'];
    
    $sql = "SELECT id, uid, name FROM students WHERE uid = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $uid, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['student_id'] = $row['id'];
        $_SESSION['student_uid'] = $row['uid'];
        $_SESSION['student_name'] = $row['name'];
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error = "Invalid UID or password";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Student Login</h1>
        
        <?php if ($error): ?>
            <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="uid" class="block text-gray-700 text-sm font-bold mb-2">UID:</label>
                <input type="text" id="uid" name="uid" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Login
                </button>
                <a href="index.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Back to Home
                </a>
            </div>
        </form>
    </div>
</body>
</html>