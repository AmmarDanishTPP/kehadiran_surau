<?php
session_start();
if ($_SESSION['role'] != 'warden') {
    header("Location: login.php");
    exit();
}

include 'connect.php';

// Fetch attendance records
$result = $conn->query("SELECT users.name, attendance.date, attendance.status FROM attendance JOIN users ON attendance.user_id = users.id");

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
        .table-actions {
            display: flex;
            justify-content: center;
        }
        .action-btn {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .action-btn:hover {
            background-color: #c82333;
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

        <!-- Attendance Table -->
        <table>
            <tr>
                <th>Student Name</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['date']) ?></td>
                <td><?= htmlspecialchars(ucfirst($row['status'])) ?></td>
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