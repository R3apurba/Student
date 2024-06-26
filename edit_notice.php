<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connection.php';

$notice_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$notice_id) {
    header("Location: manage_notices.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    
    $sql = "UPDATE notices SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $notice_id);
    
    if ($stmt->execute()) {
        $message = "Notice updated successfully";
        // Refresh the notice data after update
        $stmt->close();
        $sql = "SELECT * FROM notices WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $notice_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $notice = $result->fetch_assoc();
        $stmt->close();
    } else {
        // Log error instead of showing it directly
        error_log("Error updating notice: " . $conn->error);
        $error = "Error updating notice. Please try again later.";
    }
} else {
    $sql = "SELECT * FROM notices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $notice_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $notice = $result->fetch_assoc();
    $stmt->close();
    
    if (!$notice) {
        header("Location: manage_notices.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Notice - Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Edit Notice</h1>
        
        <?php if ($message): ?>
            <p class="text-green-600 mb-4"><?= $message ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="text-red-600 mb-4"><?= $error ?></p>
        <?php endif; ?>
        
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $notice_id; ?>" method="POST" class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($notice['title']); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content:</label>
                <textarea id="content" name="content" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="5"><?= htmlspecialchars($notice['content']); ?></textarea>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Notice
                </button>
                <a href="manage_notices.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Back to Notices
                </a>
            </div>
        </form>
    </div>
</body>
</html>