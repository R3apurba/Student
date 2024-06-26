<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $sql = "INSERT INTO notices (title, content) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $title, $content);
    
    if ($stmt->execute()) {
        $message = "Notice added successfully";
    } else {
        $error = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Notice - Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Add New Notice</h1>
        
        <?php
        if (isset($message)) {
            echo "<p class='text-green-600 mb-4'>" . $message . "</p>";
        }
        if (isset($error)) {
            echo "<p class='text-red-600 mb-4'>" . $error . "</p>";
        }
        ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                <input type="text" id="title" name="title" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Content:</label>
                <textarea id="content" name="content" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="5"></textarea>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Add Notice
                </button>
                <a href="manage_notices.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Back to Notices
                </a>
            </div>
        </form>
    </div>
</body>
</html>