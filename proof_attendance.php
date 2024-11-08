<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

include 'connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $status = 'present';  // Default status to 'present'
    
    // Set attendance date and time to the current server date and time
    $attendance_datetime = date('Y-m-d H:i:s');

    // Handle file upload
    if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['proof_image']['tmp_name'];
        $fileName = $_FILES['proof_image']['name'];
        $fileSize = $_FILES['proof_image']['size'];
        $fileType = $_FILES['proof_image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allowed file extensions
        $allowedfileExtensions = array('jpg', 'png', 'jpeg');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Absolute path to the uploads directory
            $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
            
            // Create uploads directory if it doesn't exist
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;

            // Move file to the uploads directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Save attendance record with image proof and datetime
                $stmt = $conn->prepare("INSERT INTO attendance (user_id, date, status, proof_image) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $attendance_datetime, $status, $newFileName);

                if ($stmt->execute()) {
                    echo "<div class='message success'>Attendance proof uploaded successfully!</div>";
                } else {
                    echo "<div class='message error'>Database error: " . $stmt->error . "</div>";
                }

                $stmt->close();
            } else {
                echo "<div class='message error'>Error moving the file to the upload directory.</div>";
            }
        } else {
            echo "<div class='message error'>Invalid file type. Only JPG, JPEG, and PNG files are allowed.</div>";
        }
    } else {
        echo "<div class='message error'>Please upload a valid image file.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Attendance Proof</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }
        input[type="date"],
        input[type="file"] {
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            padding: 10px;
            margin-top: 15px;
            text-align: center;
            border-radius: 5px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Submit Attendance Proof</h1>
        <form method="POST" action="proof_attendance.php" enctype="multipart/form-data">
            <label for="date">Date of Attendance:</label>
            <input type="date" id="date" name="date" required>

            <label for="proof_image">Upload Attendance Proof (Image):</label>
            <input type="file" id="proof_image" name="proof_image" accept=".jpg, .jpeg, .png" required>

            <button type="submit">Submit Proof</button>
        </form>
    </div>

</body>
</html>