<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connection.php';

$message = '';
$error = '';

$student_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$student_id) {
    header("Location: admin_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uid = htmlspecialchars($_POST['uid']);
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $dob = htmlspecialchars($_POST['dob']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $department = htmlspecialchars($_POST['department']);
    

    if (!$error) {
        $sql = "UPDATE students SET uid = ?, name = ?, email = ?, dob = ?, phone = ?, address = ?, department = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $uid, $name, $email, $dob, $phone, $address, $department, $student_id);
        
        if ($stmt->execute()) {
            $message = "Student updated successfully";
        } else {
            error_log("Error: " . $stmt->error);
            $error = "An error occurred while updating the student. Please try again.";
        }
        
        $stmt->close();
    }
} else {
    // Fetch student details for pre-filling the form
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    
    if (!$student) {
        header("Location: admin_dashboard.php");
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Information System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-4">Edit Student</h1>
        
        <?php if ($message): ?>
            <p class="text-green-600 mb-4"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . $student_id); ?>" method="POST" class="bg-white p-6 rounded shadow">
            <div class="mb-4">
                <label for="uid" class="block text-gray-700 text-sm font-bold mb-2">UID:</label>
                <input type="text" id="uid" name="uid" value="<?= isset($student['uid']) ? htmlspecialchars($student['uid']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" value="<?= isset($student['name']) ? htmlspecialchars($student['name']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="<?= isset($student['email']) ? htmlspecialchars($student['email']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="dob" class="block text-gray-700 text-sm font-bold mb-2">Date of Birth:</label>
                <input type="text" id="dob" name="dob" placeholder="dd-mm-yyyy" value="<?= isset($student['dob']) ? htmlspecialchars($student['dob']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?= isset($student['phone']) ? htmlspecialchars($student['phone']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address:</label>
                <textarea id="address" name="address" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= isset($student['address']) ? htmlspecialchars($student['address']) : ''; ?></textarea>
            </div>
            
            <div class="mb-4">
                <label for="department" class="block text-gray-700 text-sm font-bold mb-2">Department:</label>
                <input type="text" id="department" name="department" value="<?= isset($student['department']) ? htmlspecialchars($student['department']) : ''; ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Student
                </button>
                <a href="admin_dashboard.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Back to Dashboard
                </a>
            </div>
        </form>
    </div>
</body>
</html>
