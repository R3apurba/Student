<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connection.php';

// Fetch total count of students
$sql = "SELECT COUNT(*) AS total_students FROM students";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_students = $row['total_students'];

// Fetch students sorted by UID
$sql = "SELECT * FROM students ORDER BY uid ASC";
$result = $conn->query($sql);


// Initialize variables
$searched_uid = isset($_GET['uid']) ? htmlspecialchars($_GET['uid']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <style>
        .highlight {
            background-color: #fff59d; /* Yellow background */
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-white text-2xl font-bold">Admin Dashboard</a>
            <div>
                <a href="add_student.php" class="text-white mr-4">Add New Student</a>
                <a href="manage_notices.php" class="text-white mr-4">Manage Notices</a>
                <a href="logout.php" class="text-white">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Student List</h1>
        
        <div class="mb-4">
            <form action="admin_dashboard.php" method="GET" class="flex">
                <input type="text" name="uid" value="<?= htmlspecialchars($searched_uid) ?>" placeholder="Search by UID" class="p-2 border rounded-l">
                <button type="submit" class="bg-blue-500 text-white p-2 rounded-r">Search</button>
            </form>
        </div>
        <div class="mb-4">
            <p class="text-gray-600">Total Students: <span class="font-bold"><?php echo $total_students; ?></span></p>
        </div>
        <table class="w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">UID</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">DOB</th>
                    <th class="py-3 px-6 text-left">Address</th>
                    <th class="py-3 px-6 text-left">Phone Number</th>
                    <th class="py-3 px-6 text-left">Department</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $highlightClass = ($row['uid'] == $searched_uid) ? 'highlight' : '';
                        echo "<tr class='border-b border-gray-200 hover:bg-gray-100 " . $highlightClass . "'>";
                        echo "<td class='py-3 px-6 text-left whitespace-nowrap'>" . htmlspecialchars($row["uid"]) . "</td>";
                        echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row["name"]) . "</td>";
                        echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row["email"]) . "</td>";
                        echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row["dob"]) . "</td>";
                        echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row["address"]) . "</td>";
                        echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row["phone"]) . "</td>";
                        echo "<td class='py-3 px-6 text-left'>" . htmlspecialchars($row["department"]) . "</td>";
                        echo "<td class='py-3 px-6 text-center'>";
                        echo "<a href='edit_student.php?id=" . $row["id"] . "' class='text-blue-600 hover:text-blue-900 mr-2'>Edit</a>";
                        echo "<a href='delete_student.php?id=" . $row["id"] . "' class='text-red-600 hover:text-red-900' onclick='return confirm(\"Are you sure you want to delete this student?\");'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='py-3 px-6 text-center'>No students found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // JavaScript to scroll to and highlight the searched student row
        document.addEventListener("DOMContentLoaded", function() {
            var searchedUID = "<?= $searched_uid ?>";
            var rows = document.querySelectorAll("tbody tr");

            rows.forEach(function(row) {
                if (row.querySelector("td:first-child").textContent === searchedUID) {
                    row.classList.add("highlight");
                    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    </script>
</body>
</html>
