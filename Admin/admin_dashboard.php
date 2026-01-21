<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "root@123";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS iap_portal";
$conn->query($sql);

// Select the database
$conn->select_db("iap_portal");

// Create tables if not exist
$sql = "CREATE TABLE IF NOT EXISTS IAP_users_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL
);";
$conn->query($sql);

// Alter table to add email column if it doesn't exist
$check_email = $conn->query("SHOW COLUMNS FROM IAP_users_details LIKE 'email'");
if ($check_email->num_rows == 0) {
    $conn->query("ALTER TABLE IAP_users_details ADD COLUMN email VARCHAR(255) NOT NULL UNIQUE DEFAULT 'temp@example.com'");
}

$sql = "CREATE TABLE IF NOT EXISTS session_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    roll_number VARCHAR(50) NOT NULL,
    year ENUM('1', '2', '3', '4') NOT NULL,
    department VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    session_desired VARCHAR(255) NOT NULL,
    other_query TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
$conn->query($sql);

$sql = "CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic VARCHAR(255) NOT NULL,
    year ENUM('1', '2', '3', '4') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
$conn->query($sql);

// Insert default admin if not exists
$sql = 'INSERT IGNORE INTO IAP_users_details (username, email, password, role) VALUES (\'admin\', \'admin@example.com\', \'$2y$10$xHDNFM0xYFstLYe.BIHMUu4ZxCcEeKOQ3psUy85ZcbsCqdbWUy2Z.\', \'admin\')';
$conn->query($sql);

$message = '';
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_session'])) {
    $topic = $_POST['topic'];
    $year = $_POST['year'];

    $sql = "INSERT INTO sessions (topic, year) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $topic, $year);
    if ($stmt->execute()) {
        $message = "Session created successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
    $stmt->close();
}

if ($page == 'registrations') {
    $sql = "SELECT * FROM session_registrations ORDER BY submitted_at DESC";
    $result = $conn->query($sql);
} else if ($page == 'registered_students') {
    // Fetch all students who registered through student_sessions
    $sql = "SELECT DISTINCT 
                s.id,
                s.full_name,
                s.email,
                s.roll_number,
                s.department,
                s.year,
                COUNT(DISTINCT ss.session_id) as sessions_count,
                GROUP_CONCAT(DISTINCT sess.topic SEPARATOR ', ') as registered_sessions
            FROM students s
            LEFT JOIN student_sessions ss ON s.id = ss.student_id
            LEFT JOIN sessions sess ON ss.session_id = sess.id
            GROUP BY s.id
            ORDER BY s.created_at DESC";
    $registered_students_result = $conn->query($sql);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - IAP Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Roboto, Arial, sans-serif;
        }

        body {
            background: #fbfcff;
            color: #374151;
            line-height: 1.6;
        }

        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            padding: 20px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f3e8ff;
        }

        .sidebar-logo img {
            height: 80px;
            width: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .sidebar h2 {
            color: #5b21b6;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin-bottom: 10px;
        }

        .sidebar a {
            text-decoration: none;
            color: #374151;
            padding: 10px;
            display: block;
            border-radius: 8px;
            transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #f3e8ff;
            color: #5b21b6;
        }

        .main-content {
            flex: 1;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .logout-btn {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #5b21b6;
        }

        .section-title {
            color: #5b21b6;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
        }

        .btn {
            background: #7c3aed;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background: #5b21b6;
        }

        .message {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f3e8ff;
            color: #5b21b6;
            font-weight: 600;
        }

        tr:hover {
            background: #f9fafb;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="../images/SA Main logo.jpg" alt="SA Main Logo" title="SA Main">
            </div>
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="?page=home" class="<?php echo $page == 'home' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="?page=create_session" class="<?php echo $page == 'create_session' ? 'active' : ''; ?>">Create Session</a></li>
                <li><a href="?page=registrations" class="<?php echo $page == 'registrations' ? 'active' : ''; ?>">View Session Registrations</a></li>
                <li><a href="?page=registered_students" class="<?php echo $page == 'registered_students' ? 'active' : ''; ?>">View Registered Students</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <a href="../logout.php" class="logout-btn">Logout</a>
            </div>

            <?php if ($message): ?>
                <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($page == 'home'): ?>
                <h2 class="section-title">Welcome to Admin Dashboard</h2>
                <p>Use the sidebar to navigate to different sections.</p>

            <?php elseif ($page == 'create_session'): ?>
                <h2 class="section-title">Create New Session</h2>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="topic">Topic:</label>
                        <input type="text" id="topic" name="topic" required>
                    </div>
                    <div class="form-group">
                        <label for="year">Year:</label>
                        <select id="year" name="year" required>
                            <option value="1">Year 1</option>
                            <option value="2">Year 2</option>
                            <option value="3">Year 3</option>
                            <option value="4">Year 4</option>
                        </select>
                    </div>
                    <button type="submit" name="create_session" class="btn">Create Session</button>
                </form>

            <?php elseif ($page == 'registrations'): ?>
                <h2 class="section-title">Session Registrations</h2>
                <?php if ($result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Roll Number</th>
                                <th>Year</th>
                                <th>Department</th>
                                <th>Email</th>
                                <th>Session Desired</th>
                                <th>Other Query</th>
                                <th>Submitted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['year']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['session_desired']); ?></td>
                                    <td><?php echo htmlspecialchars($row['other_query']); ?></td>
                                    <td><?php echo htmlspecialchars($row['submitted_at']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">No registrations yet.</p>
                <?php endif; ?>

            <?php elseif ($page == 'registered_students'): ?>
                <h2 class="section-title">Registered Students via Student Portal</h2>
                <?php if (isset($registered_students_result) && $registered_students_result->num_rows > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Roll Number</th>
                                <th>Department</th>
                                <th>Year</th>
                                <th>Sessions Registered</th>
                                <th>Registered Sessions</th>
                                <th>Quizzes Taken</th>
                                <th>Modules Completed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $registered_students_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['roll_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department']); ?></td>
                                    <td>Year <?php echo htmlspecialchars($row['year']); ?></td>
                                    <td><strong><?php echo $row['sessions_count']; ?></strong></td>
                                    <td>
                                        <small><?php 
                                            echo $row['registered_sessions'] ? htmlspecialchars($row['registered_sessions']) : '<em>None</em>'; 
                                        ?></small>
                                    </td>
                                    <td>
                                        <!-- Dummy value: Random quiz count between 0-5 -->
                                        <span style="background: #e0f7e0; padding: 4px 8px; border-radius: 4px; font-weight: 600; color: #15803d;">
                                            <?php echo rand(0, 5); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <!-- Dummy value: Random module count between 0-3 -->
                                        <span style="background: #e0e7ff; padding: 4px 8px; border-radius: 4px; font-weight: 600; color: #1e3a8a;">
                                            <?php echo rand(0, 3); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">No students registered yet through the student portal.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
