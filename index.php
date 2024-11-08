<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance System - Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header */
        header {
            background-color: #343a40;
            padding: 20px;
            color: white;
        }
        header h1 {
            margin: 0;
            display: inline-block;
        }
        nav {
            float: right;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        nav a:hover {
            text-decoration: underline;
        }

        /* Main content */
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 50px 20px;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .button-group {
            margin-top: 30px;
        }
        a {
            text-decoration: none;
            background-color: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 5px;
            margin: 10px;
            display: inline-block;
        }
        a:hover {
            background-color: #218838;
        }
        .register-btn {
            background-color: #007bff;
        }
        .register-btn:hover {
            background-color: #0069d9;
        }

        /* Footer */
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
            margin-top: 50px;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>

    <!-- Header section -->
    <header>
        <div class="container">
            <h1>Sistem Kehadiran</h1>
            <nav>
                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
                <a href="contact.php">Contact</a> <!-- You can link this to a contact page -->
            </nav>
        </div>
    </header>

    <!-- Main content -->
    <div class="container">
        <h2>Selamat Datang Ke Sistem Kehadiran Surau</h2>
        <p>Sistem ini membolehkan pelajar menyerahkan bukti kehadiran mereka, dan warden boleh mengurus dan mengesahkan rekod kehadiran.</p>
        <div class="button-group">
            <!-- Button to Registration Page -->
            <a href="register.php" class="register-btn">Register</a>
        </div>
    </div>

    <!-- Footer section -->
    <footer>
        <p>&copy; 2024 Attendance System. All rights reserved.</p>
        <p>Contact us: info@attendancesystem.com</p>
    </footer>

</body>
</html>