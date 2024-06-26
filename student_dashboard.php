<?php
session_start();
include 'db_connection.php';

// Check if the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: student_login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch student details
$sql = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Handle form submission for updating address and phone
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_address = $_POST['address'];
    $new_phone = $_POST['phone'];
    
    $update_sql = "UPDATE students SET address = ?, phone = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $new_address, $new_phone, $student_id);
    
    if ($update_stmt->execute()) {
        $success_message = "Your information has been updated successfully.";
        // Refresh student details
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
    } else {
        $error_message = "An error occurred while updating your information.";
    }
    
    $update_stmt->close();
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100 p-4">
    <div class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold mb-4">Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h1>
        
        <?php
        if (isset($success_message)) {
            echo "<p class='text-green-600 mb-4'>" . $success_message . "</p>";
        }
        if (isset($error_message)) {
            echo "<p class='text-red-600 mb-4'>" . $error_message . "</p>";
        }
        ?>
        
        <div class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Your Information</h2>
            <p><strong>UID:</strong> <?php echo htmlspecialchars($student['uid']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['dob']); ?></p>
            <p><strong>Department:</strong> <?php echo htmlspecialchars($student['department']); ?></p>
        </div>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Update Your Information</h2>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($student['address']); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Information
            </button>
        </form>
        
        <div class="flex justify-left mb-4">
        <a href="change_password.php" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mr-2">
    Change Password
</a>
<a href="logout.php" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-2">
    Logout
</a>

        </div>
    </div>
</body>
</html>