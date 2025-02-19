<?php
session_start();

// Define session timeout duration (e.g., 30 minutes)
$timeoutDuration = 1800; // 30 minutes in seconds

// Check if "lastActivity" is set in the session
if (isset($_SESSION['lastActivity'])) {
    // Calculate the session's lifetime
    $elapsedTime = time() - $_SESSION['lastActivity'];

    // If the session has expired
    if ($elapsedTime > $timeoutDuration) {
        // Unset all session variables
        session_unset();

        // Destroy the session
        session_destroy();

        // Force logout redirect
        header("Location: index.php");
        exit();
    }
}

// Fetch profile information
require_once 'utilities/db_connection.php';

try {
    $stmt = $pdo->prepare("SELECT * from profile");
    $stmt->execute();
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profile) {
        throw new Exception("Profile information not found.");
    }
} catch (PDOException $e) {
    die("Error fetching profile: " . $e->getMessage());
} catch (Exception $e) {
    die($e->getMessage());
}

// Fetch company names
try {
    $stmt = $pdo->prepare("
        SELECT c.company_name, c.company_id, MAX(j.start_date) AS latest_start_date
        FROM companies c
        INNER JOIN Job_history j ON c.company_id = j.company_id
        GROUP BY c.company_name, c.company_id
        ORDER BY latest_start_date DESC;
    ");
    $stmt->execute();
    $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching companies: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($profile['profile_name']); ?></title>
    <link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sidebar styling */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #343a40;
            color: #fff;
            padding-top: 20px;
            display: none; /* Sidebar hidden initially */
        }

        #sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            font-size: 1.2rem;
        }

        #sidebar a:hover {
            background-color: #575757;
        }

        #logoutButtonSidebar {
            position: absolute;
            bottom: 20px;
            left: 10px;
        }

        .navbar {
            display: block;
        }

        .hide-navbar {
            display: none;
        }
        
        iframe {
            margin-left: 250px; /* Avoid sidebar overlap */
            width: calc(100% - 250px);
            height: 100vh;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const accessLevel = sessionStorage.getItem('accessLevel');
            checkAccessLevel(accessLevel);
        });

        function checkAccessLevel(accessLevel) {
            const addUpdateButton = document.querySelector('#addUpdateButton');
            const logoutButton = document.querySelector('#logoutButton');
            const loginButton = document.querySelector('#loginButton');
            const sidebar = document.getElementById('sidebar');
            const navbar = document.getElementById('navbarNav');

            if (accessLevel === 'admin') {
                addUpdateButton.style.display = 'block';
            } else {
                addUpdateButton.style.display = 'none';
            }

            if (accessLevel && accessLevel !== 'view') {
                sidebar.style.display = 'block'; // Show sidebar after login
                navbar.classList.add('hide-navbar'); // Hide navbar after login
                logoutButton.style.display = 'block';
                loginButton.style.display = 'none';
            } else {
                sidebar.style.display = 'none'; // Hide sidebar before login
                navbar.classList.remove('hide-navbar'); // Show navbar before login
                logoutButton.style.display = 'none';
                loginButton.style.display = 'block';
            }
        }

        // Handle login via AJAX
        async function handleLogin(event) {
            event.preventDefault();

            const username = document.querySelector('#username').value;
            const password = document.querySelector('#password').value;

            try {
                const response = await fetch('authenticate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({ username: username, password: password }),
                });

                const result = await response.json();

                if (result.success) {
                    sessionStorage.setItem('accessLevel', result.accessLevel);
                    checkAccessLevel(result.accessLevel);
                    document.querySelector('#loginModal .btn-close').click();
                    alert('Login successful!');
                } else {
                    alert('Login failed: ' + result.message);
                }
            } catch (error) {
                alert('An error occurred: ' + error.message);
            }
        }

  function handleLogout() {
    sessionStorage.removeItem('accessLevel');
    fetch('logout.php', { method: 'POST' })
        .then(() => {
            checkAccessLevel('view'); // Update UI to reflect logged-out state
            alert('Logged out successfully!');
            window.location.href = 'index.php'; // Redirect to index.php after logout
        });
}


        // Load content into iframe
        function updateIframe(src) {
            document.getElementById('nav').src = src;
        }
    </script>
</head>
<body>
    <main>
        <!-- Navbar for before login -->
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark" id="navbarNav">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" onclick="updateIframe('base.php')">
                    <img src="images/DDAYLOGO.gif" height="70"><?php echo htmlspecialchars($profile['profile_name']); ?>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php if (!empty($companies)): ?>
                            <?php foreach ($companies as $company): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="#" onclick="updateIframe('byid.php?company_id=<?php echo htmlspecialchars($company['company_id']); ?>')">
                                        <?php echo htmlspecialchars($company['company_name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#">No Companies Available</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <button id="addUpdateButton" type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#modal" style="display: none;">Add/Update</button>
                    <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#loginModal" id="loginButton">
                        <img src="images/gear2.png" height="25" width="25">
                    </button>
                    <button type="button" class="btn btn-danger ms-2" id="logoutButton" style="display: none;" onclick="handleLogout()">Logout</button>
                </div>
            </div>
        </nav>

        <!-- Sidebar for after login -->
        <div id="sidebar">
            <a href="#" onclick="updateIframe('pdfresume.php')">> PDF Resume</a>
            <a href="#" onclick="updateIframe('config.php')">> Config</a>
            <a href="#" onclick="updateIframe('accounts.php')">> Accounts</a>
            <a href="#" onclick="updateIframe('profiles.php')">> Profile</a>
            <a href="#" onclick="updateIframe('companies.php')">> Companies</a>
            <a href="#" onclick="updateIframe('jobs.php')">> Jobs</a>
            <a href="#" onclick="updateIframe('skills.php')">> Skills</a>
            <button id="logoutButtonSidebar" class="btn btn-danger" onclick="handleLogout()">Logout</button>
        </div>

        <!-- Main Content Area -->
        <div class="w-100 p-3" id="nav-space">
            <iframe id="nav" src="base.php" width="100%" height="100%" scrolling="no" onload="resizeIframe(this)"></iframe>
        </div>

        <!-- Login Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="loginForm">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="./assets/dist/js/bootstrap.bundle.min.js"></script>
    </main>
</body>
</html>
