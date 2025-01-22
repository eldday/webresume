<?php
session_start();
if (isset($_SESSION['accessLevel'])) {
//echo json_encode(['success' => true, 'accessLevel' => $_SESSION['accessLevel']]);
}
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

        exit();
    }
}
// Include your database connection script
require_once 'db_connection.php';

// Fetch profile information
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
        SELECT DISTINCT c.company_name, c.company_id
        FROM companies c
        INNER JOIN Job_history j ON c.company_id = j.company_id
        ORDER BY start_date DESC;
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
    <title>Patrick D. Day</title>
    <link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      /* Your existing styles here */
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
     .modal-title h4 {
	font-size: 3.0rem;
	}
    </style>
  <script>
	document.addEventListener('DOMContentLoaded', () => {
	    fetch('checkAuth.php', { cache: 'no-store' })
	        .then(response => response.json())
	        .then(result => {
	            if (result.success) {
	                sessionStorage.setItem('accessLevel', result.accessLevel);
	                checkAccessLevel(result.accessLevel);
	            } else {
	                sessionStorage.removeItem('accessLevel');
	                checkAccessLevel('view');
	            }
	        });
	});

      function checkAccessLevel(accessLevel) {
        const addUpdateButton = document.querySelector('#addUpdateButton');
        const logoutButton = document.querySelector('#logoutButton');
        const loginButton = document.querySelector('#loginButton');

        if (accessLevel === 'admin') {
          addUpdateButton.style.display = 'block';
        } else {
          addUpdateButton.style.display = 'none';
        }

        // Show logout button and hide login button if logged in
        if (accessLevel !== 'view') {
          logoutButton.style.display = 'block';
          loginButton.style.display = 'none';
        } else {
          logoutButton.style.display = 'none';
          loginButton.style.display = 'block';
        }
      }

      // Simulate fetching access level from server (replace with actual server response)
      document.addEventListener('DOMContentLoaded', () => {
        const accessLevel = sessionStorage.getItem('accessLevel') || 'view'; // Example: 'admin' or 'view'
        checkAccessLevel(accessLevel);
      });

      // Handle login via AJAX
      async function handleLogin(event) {
        event.preventDefault(); // Prevent full-page reload

        const loginId = document.querySelector('#login_id').value;
        const loginPword = document.querySelector('#login_pword').value;

        try {
          const response = await fetch('authenticate.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ login_id: loginId, login_pword: loginPword }),
          });

          const result = await response.json();

          if (result.success) {
            sessionStorage.setItem('accessLevel', result.accessLevel); // Store access level
            checkAccessLevel(result.accessLevel); // Update UI dynamically
            document.querySelector('#loginModal .btn-close').click(); // Close the modal
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
            location.reload(); // Force reload to ensure fresh session state
        });
}


</script>

  </head>
  <body>
    <main>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="" data-bs-target="refreshsite()" onclick="refreshsite()">
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
                    <a class="nav-link" href="byid.php?company_id=<?php echo htmlspecialchars($company['company_id']); ?>" id="<?php echo htmlspecialchars($company['company_name']); ?>" target="nav">
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
            <button type="button" class="btn btn-danger ms-2" id="logoutButton" style="display: none;" onclick="handleLogout()">
              Logout
            </button>
          </div>
        </div>
      </nav>

      <!-- Existing Modals and Content -->
      <div class="modal fade bd-example-modal-xl" id="modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title h4" id="exampleModalFullscreenLabel">Add/Update Records:</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <iframe id="navmodal" src="info.htm" width="100%" height="450" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" onclick="updateIframe('accounts.php')">Users</button>
	      <button type="button" class="btn btn-secondary" onclick="updateIframe('profiles.php')">Profile</button>	
              <button type="button" class="btn btn-secondary" onclick="updateIframe('companies.php')">Companies</button>
              <button type="button" class="btn btn-secondary" onclick="updateIframe('jobs.php')">Jobs</button>
              <button type="button" class="btn btn-secondary" onclick="updateIframe('skills.php')">Skills</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

	<script>
	  function updateIframe(src) {
	    document.getElementById('navmodal').src = src;
	  }
	</script>

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
                  <label for="login_id" class="form-label">Username</label>
                  <input type="text" class="form-control" id="login_id" name="login_id" required>
                </div>
                <div class="mb-3">
                  <label for="login_pword" class="form-label">Password</label>
                  <input type="password" class="form-control" id="login_pword" name="login_pword" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
              </form>
            </div>
          </div>
        </div>
      </div>

     <div class="w-100 p-3" id="nav-space">
        <br><br>
        <iframe id="nav" src="base.php" width="100%" height="100%" scrolling="no" onload="resizeIframe(this)"></iframe>
      </div>

      <script src="./assets/dist/js/bootstrap.bundle.min.js"></script>
      <script>
         document.querySelectorAll('navmodal a').forEach(link => {
          link.addEventListener('click', function(event) {
            event.preventDefault();
            const iframe = document.getElementById('navmodal');
            iframe.src = this.href;
          });
        });

        // Resize iframe to fit content dynamically
        function resizeIframe(iframe) {
          iframe.style.height = iframe.contentWindow.document.body.scrollHeight + 'px';
        }

        // Attach event listeners to navigation links
        document.querySelectorAll('nav a').forEach(link => {
          link.addEventListener('click', function(event) {
            event.preventDefault();
            const iframe = document.getElementById('nav');
            iframe.src = this.href;
          });
        });

        function refreshPage() {
                location.reload("nav");
	        }

        function refreshsite() {
        location.reload(parent);
        	}

        function home() {
        $("#nav").load(parent)
        	}

        // Attach event listener to login form
        document.querySelector('#loginForm').addEventListener('submit', handleLogin);
      </script>
     </main>
  </body>
</html>
