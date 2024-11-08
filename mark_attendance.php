<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'warden') {
    header("Location: login.php");
    exit();
}

include 'connect.php';

// Handle attendance marking if a form submission is made
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $attendance_id = $_POST['attendance_id'];
    $status = $_POST['status'];

    // Update attendance status in the database
    $stmt = $conn->prepare("UPDATE attendance SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $attendance_id);

    if ($stmt->execute()) {
        echo "<script>alert('Attendance updated successfully');</script>";
        echo "<script>window.location.href='warden_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating attendance: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Handle delete functionality if a delete request is made
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete attendance record from the database
    $stmt = $conn->prepare("DELETE FROM attendance WHERE id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Attendance record deleted successfully');</script>";
        echo "<script>window.location.href='warden_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting attendance record: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch all attendance records for the warden to view
$result = $conn->query("SELECT attendance.id, users.name, attendance.date, attendance.status, attendance.proof_image FROM attendance JOIN users ON attendance.user_id = users.id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warden Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #007bff;
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            margin: 0;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #dc3545;
            border-radius: 5px;
        }
        .navbar a:hover {
            background-color: #c82333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .add-btn:hover {
            background-color: #218838;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .proof-img {
            max-width: 100px;
            height: auto;
        }
        .attendance-form {
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>Warden Dashboard</h1>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Attendance Records</h2>

        <a href="mark_attendance.php" class="add-btn">Mark Attendance</a>

        <!-- Attendance Table -->
        <table>
            <tr>
                <th>Student Name</th>
                <th>Date and Time Submitted</th>
                <th>Status</th>
                <th>Proof Image</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars(date('Y-m-d H:i:s', strtotime($row['date']))) ?></td> <!-- Displaying date and time -->
                <td>
                    <form method="POST" class="attendance-form">
                        <input type="hidden" name="attendance_id" value="<?= $row['id'] ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option value="present" <?= $row['status'] == 'present' ? 'selected' : '' ?>>Present</option>
                            <option value="absent" <?= $row['status'] == 'absent' ? 'selected' : '' ?>>Absent</option>
                        </select>
                    </form>
                </td>
                <td>
                    <?php if ($row['proof_image']): ?>
                        <img src="uploads/<?= htmlspecialchars($row['proof_image']) ?>" class="proof-img" alt="Proof Image">
                    <?php else: ?>
                        No Proof Submitted
                    <?php endif; ?>
                </td>
                <td>
                    <!-- Option to delete the attendance record -->
                    <a href="warden_dashboard.php?delete_id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <?php if ($result->num_rows == 0): ?>
            <p>No attendance records found.</p>
        <?php endif; ?>
    </div>

</body>
</html>

<?php
$conn->close();
?>