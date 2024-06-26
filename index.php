<?php
// Include database connection
include 'db_connection.php';

// Fetch notices from the database
$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        .notice-board {
            max-height: 400px;
            overflow-y: auto;
        }
        .notice-board::-webkit-scrollbar {
            width: 8px;
        }
        .notice-board::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .notice-board::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .notice-board::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">
    <nav class="bg-blue-600 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <p class="text-white text-2xl font-bold">SIS</p>
            <div>
                <a href="student_login.php" class="text-white mr-4">Student Login</a>
                <a href="admin_login.php" class="text-white">Admin Login</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 px-4 sm:px-6 lg:px-8 flex-grow">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Welcome to Student Information System</h1>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4 text-indigo-700">Notice Board</h2>
            <div class="notice-board">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-indigo-100'>";
                        echo "<h3 class='font-semibold text-lg text-indigo-800'>" . htmlspecialchars($row["title"]) . "</h3>";
                        echo "<p class='mt-2 text-gray-700'>" . htmlspecialchars($row["content"]) . "</p>";
                        echo "<small class='text-indigo-600 mt-2 block'>Posted on: " . $row["created_at"] . "</small>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-gray-600 italic'>No notices at this time.</p>";
                }
                ?>
            </div>
        </div>
    </div>

    <footer class="bg-blue-600 text-white text-xs py-2">
        <div class="container mx-auto text-center">
            Developed by Apurba
        </div>
    </footer>
</body>
</html>