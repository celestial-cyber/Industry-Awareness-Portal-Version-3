<?php
/**
 * Student Dashboard
 * Displays personalized dashboard with registered sessions
 * Shows only sessions the student is registered for
 * Includes "Take Quiz" button for each session
 */

// Include session protection - must be at the top
require_once 'includes/student_session_check.php';

$error_message = '';
$registered_sessions = [];

try {
    // Fetch student's registered sessions using MySQLi prepared statement
    $sql = "SELECT 
                s.id,
                s.topic as title,
                s.year,
                '' as description,
                ss.registration_status,
                ss.registered_at
            FROM sessions s
            JOIN student_sessions ss ON s.id = ss.session_id
            WHERE ss.student_id = ?
            ORDER BY s.year ASC, s.topic ASC";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        $error_message = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("i", $_SESSION['student_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $registered_sessions[] = $row;
        }
        
        $stmt->close();
    }
} catch (Exception $e) {
    $error_message = "Error fetching sessions: " . $e->getMessage();
}

// Group sessions by year
$sessions_by_year = [];
foreach ($registered_sessions as $session) {
    $year = $session['year'];
    if (!isset($sessions_by_year[$year])) {
        $sessions_by_year[$year] = [];
    }
    $sessions_by_year[$year][] = $session;
}

// Logout function
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: student_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - IAP Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Theme CSS -->
    <link rel="stylesheet" href="theme.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            flex-direction: column;
        }

        .dashboard-wrapper {
            display: flex;
            flex: 1;
        }

        /* Sidebar */
        .dashboard-sidebar {
            width: 200px;
            background: white;
            border-right: 1px solid #e0e0e0;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 70px;
            height: calc(100vh - 70px);
            overflow-y: auto;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .sidebar-logo img {
            height: 70px;
            width: 70px;
            border-radius: 50%;
            object-fit: cover;
        }

        .main-dashboard-content {
            margin-left: 200px;
            flex: 1;
            width: calc(100% - 200px);
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        .dashboard-container {
            padding: 30px 20px;
            flex: 1;
        }

        .container-lg {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            padding: 0 20px;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            font-weight: 700;
            font-size: 20px;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
            padding: 0.5rem 1rem !important;
        }

        .navbar-custom .nav-link:hover {
            color: white !important;
        }

        .navbar-nav {
            gap: 20px;
            align-items: center;
        }

        .navbar-nav .nav-item {
            display: flex;
            align-items: center;
        }

        .user-info {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .user-info small {
            display: block;
            font-size: 12px;
            opacity: 0.9;
        }

        .user-name {
            font-weight: 600;
            color: white;
        }

        /* Main Content */
        .dashboard-container {
            padding: 30px 20px;
            flex: 1;
        }

        .welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .welcome-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .welcome-header p {
            font-size: 16px;
            margin: 0;
            opacity: 0.95;
        }

        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .info-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            border-left: 3px solid white;
        }

        .info-badge strong {
            color: white;
        }

        /* Section Title */
        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }

        /* Year Section */
        .year-section {
            margin-bottom: 50px;
        }

        .year-header {
            background: #f0f4ff;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .year-header h2 {
            font-size: 20px;
            color: #667eea;
            margin: 0;
            font-weight: 700;
        }

        /* Session Cards */
        .sessions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .session-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .session-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .session-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
        }

        .session-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 10px 0;
            line-height: 1.4;
        }

        .session-year-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.3);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .session-card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .session-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            flex-grow: 1;
        }

        .session-meta {
            display: flex;
            gap: 15px;
            font-size: 13px;
            color: #999;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .status-registered {
            background: #e0f7e0;
            color: #15803d;
        }

        .status-completed {
            background: #e0e7ff;
            color: #1e3a8a;
        }

        .status-dropped {
            background: #fee2e2;
            color: #991b1b;
        }

        .quiz-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        .quiz-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .quiz-button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .empty-state-icon {
            font-size: 60px;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 22px;
            color: #374151;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .empty-state p {
            color: #6b7280;
            font-size: 16px;
            margin: 0;
        }

        /* Alert */
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #fff5f5;
            color: #c53030;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #15803d;
        }

        .alert-info {
            background-color: #f0f9ff;
            color: #0369a1;
        }

        /* Footer */
        .dashboard-footer {
            text-align: center;
            padding: 30px 20px;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .dashboard-sidebar {
                display: none;
            }

            .main-dashboard-content {
                margin-left: 0;
                width: 100%;
            }

            .welcome-header {
                padding: 25px 20px;
            }

            .welcome-header h1 {
                font-size: 24px;
            }

            .sessions-grid {
                grid-template-columns: 1fr;
            }

            .student-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-lg">
            <a class="navbar-brand" href="index.php">
                IAP Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <div class="user-info">
                            <i class="fas fa-user-circle"></i> 
                            <div>
                                <div class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                                <small><?php echo htmlspecialchars($_SESSION['email']); ?></small>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?logout=1">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="dashboard-sidebar">
        <div class="sidebar-logo">
            <img src="images/SA Main logo.jpg" alt="SA Main Logo" title="SA Main">
        </div>
        <div style="text-align: center; font-size: 12px; color: #666; margin-top: 10px;">
            <p style="margin: 0; font-weight: 600;">IAP Portal</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-dashboard-content">
        <div class="container-lg">
            <!-- Welcome Header -->
            <div class="welcome-header">
                <h1><i class="fas fa-chart-line"></i> Welcome, <?php echo htmlspecialchars(explode(' ', $_SESSION['full_name'])[0]); ?>!</h1>
                <p>Here are the IAP sessions you have registered for. Click "Take Quiz" to participate in a session's quiz.</p>
                
                <div class="student-info-grid">
                    <div class="info-badge">
                        <strong>Roll Number:</strong> <?php echo htmlspecialchars($_SESSION['roll_number']); ?>
                    </div>
                    <div class="info-badge">
                        <strong>Department:</strong> <?php echo htmlspecialchars($_SESSION['department']); ?>
                    </div>
                    <div class="info-badge">
                        <strong>Year:</strong> Year <?php echo htmlspecialchars($_SESSION['year']); ?>
                    </div>
                    <div class="info-badge">
                        <strong>Sessions Registered:</strong> <?php echo count($registered_sessions); ?>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Sessions Content -->
            <?php if (!empty($sessions_by_year)): ?>
                <h2 class="section-title"><i class="fas fa-list"></i> Your Registered Sessions</h2>

                <?php foreach (['1', '2', '3', '4'] as $year): ?>
                    <?php if (isset($sessions_by_year[$year])): ?>
                        <div class="year-section">
                            <div class="year-header">
                                <h2>Year <?php echo $year; ?> Sessions</h2>
                            </div>

                            <div class="sessions-grid">
                                <?php foreach ($sessions_by_year[$year] as $session): ?>
                                    <div class="session-card">
                                        <div class="session-card-header">
                                            <h3 class="session-title"><?php echo htmlspecialchars($session['title']); ?></h3>
                                            <span class="session-year-badge">Year <?php echo htmlspecialchars($session['year']); ?></span>
                                        </div>

                                        <div class="session-card-body">
                                            <?php if (!empty($session['description'])): ?>
                                                <p class="session-description"><?php echo htmlspecialchars($session['description']); ?></p>
                                            <?php endif; ?>

                                            <div class="session-meta">
                                                <div class="meta-item">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <?php echo date('M d, Y', strtotime($session['registered_at'])); ?>
                                                </div>
                                            </div>

                                            <!-- Status Badge -->
                                            <span class="status-badge status-<?php echo htmlspecialchars($session['registration_status']); ?>">
                                                <i class="fas fa-check-circle"></i> 
                                                <?php echo ucfirst(htmlspecialchars($session['registration_status'])); ?>
                                            </span>

                                            <!-- Take Quiz Button -->
                                            <a href="quiz.php?session_id=<?php echo htmlspecialchars($session['id']); ?>" class="quiz-button">
                                                <i class="fas fa-pen-fancy"></i> Take Quiz
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3>No Registered Sessions</h3>
                    <p>You haven't registered for any sessions yet. Please contact your administrator to register.</p>
                </div>
            <?php endif; ?>

            <!-- Footer -->
            <div class="dashboard-footer">
                <p>&copy; <?php echo date('Y'); ?> IAP Portal - SPECANCIENS. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
