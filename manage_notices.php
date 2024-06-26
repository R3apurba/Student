<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connection.php';

// Fetch notices
$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notices - Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Manage Notices</h1>
        
        <a href="add_notice.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add New Notice</a>
            <a href="admin_dashboard.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Back to Dashboard</a>
        
        
        <table class="w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Title</th>
                    <th class="py-3 px-6 text-left">Content</th>
                    <th class="py-3 px-6 text-left">Created At</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr class='border-b border-gray-200 hover:bg-gray-100'>";
                        echo "<td class='py-3 px-6 text-left whitespace-nowrap'>" . htmlspecialchars($row["title"]) . "</td>";
                        echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars(substr($row["content"], 0, 50)) . "...</td>";
                        echo "<td class='py-3 px-6 text-left'>" . $row["created_at"] . "</td>";
                        echo "<td class='py-3 px-6 text-center'>";
                        echo "<a href='edit_notice.php?id=" . $row["id"] . "' class='text-blue-600 hover:text-blue-900 mr-2'>Edit</a>";
                        echo "<a href='delete_notice.php?id=" . $row["id"] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"Are you sure you want to delete this notice?\");'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='py-3 px-6 text-center'>No notices found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>