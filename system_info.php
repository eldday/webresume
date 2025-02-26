<?php
session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";
    exit();
}

require_once 'utilities/db_connection.php'; // Include database connection

$db_config = [];

// Load the content of the db_connection.php file as a string
$db_connection_file = file_get_contents('utilities/db_connection.php');

// Regular expressions to find the DB configurations in the file
preg_match('/\$dbhost\s*=\s*\'([^\']+)\'/', $db_connection_file, $dbhost_match);
preg_match('/\$dbname\s*=\s*\'([^\']+)\'/', $db_connection_file, $dbname_match);
preg_match('/\$username\s*=\s*\'([^\']+)\'/', $db_connection_file, $username_match);

// Extract the database details using the regular expression matches
if (!empty($dbhost_match)) {
    $db_config['Database Host'] = $dbhost_match[1];
}
if (!empty($dbname_match)) {
    $db_config['Database Name'] = $dbname_match[1];
}
if (!empty($username_match)) {
    $db_config['Database User'] = $username_match[1];
}

// Fetch system details
$system_info = [
    "Hostname" => 'resume.ddayzed.com',
    "Operating System" => php_uname(),
    "Server IP" => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
    "Client IP" => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    "PHP Version" => phpversion(),
    "Server Software" => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    "Current User" => get_current_user()
];

// Fetch networking details (with IPv4 filtering)
$network_info = [
    "Domain Name" => trim(shell_exec('hostname -d 2>/dev/null')) ?: "ddayzed.com",
    "Local IP Address" => trim(shell_exec("hostname -I | awk '{print $1}' | grep -E '^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$'")) ?: $_SERVER['SERVER_ADDR'] ?? "Unknown", // Only IPv4
    "Gateway IP" => trim(shell_exec("ip route | grep default | awk '{print $3}' | grep -E '^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$'")) ?: "Unknown", // Only IPv4
    "DNS Servers" => trim(shell_exec("nmcli dev show | grep 'IP4.DNS' | awk '{print $2}' | paste -sd ', ' - | grep -E '^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$'")) ?: "Unknown", // Only IPv4
];

// Fetch public IP using ipinfo.io API (IPv4 only)
$public_ip = file_get_contents("http://ipinfo.io/ip"); // Get public IP from ipinfo.io
$network_info["Public IP Address"] = filter_var($public_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? $public_ip : "Unknown"; // Only IPv4
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>System Info</title>
    <link href="./assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/modal-style.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center;     /* Center vertically */
            height: 100vh;           /* Full height of the viewport */
            margin: 0;               /* Remove default margin */
            font-family: Arial, sans-serif;
            background-color: #a9b8c2;
        }

        table {
            border-collapse: collapse;
            border: 1px solid rgb(140, 140, 140);
            font-size: 1.0rem;
            width: 80%; /* Table width set to 80% */
            border-radius: 10px;
            box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.2);
            text-align: left; /* Align data to the left */
        }

        th, td {
            border: 1px solid rgb(160, 160, 160);
            padding: 12px; /* Increase padding for better readability */
            text-align: left;
            border-radius: 5px;
        }

        th {
            background-color: #333;
            color: #09D;
            text-align: center; /* Center align the header */
        }

        td {
            background-color: #fff;
            text-align: left; /* Align content to left */
        }

        h3 {
            color: #333;
        }

        .right-column {
            width: 70%;
            background-color: #d0d2d3;
            font-family: 'Arial';
            font-size: .9rem;
            margin: 10px;
        }

        .left-column {
            width: 30%;
            font-family: 'Arial';
            font-size: 1.0rem;
            text-align: right;
            vertical-align: top;
        }

        .header {
            background-color: #444;
            width: 100%;
            font-size: 1.3rem;
            text-align: center;
            vertical-align: center;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th colspan="2" class="header">System Info</th>
        </tr>
        <!-- System Info Row -->
        <tr>
            <th class="left-column">System Information</th>
            <td class="right-column">
                <?php foreach ($system_info as $key => $value): ?>
                    <div><strong><?php echo htmlspecialchars($key); ?>:</strong> <?php echo htmlspecialchars($value); ?></div>
                <?php endforeach; ?>
            </td>
        </tr>

        <!-- Networking Info Row -->
        <tr>
            <th class="left-column">Networking Information</th>
            <td class="right-column">
                <?php foreach ($network_info as $key => $value): ?>
                    <div><strong><?php echo htmlspecialchars($key); ?>:</strong> <?php echo htmlspecialchars($value); ?></div>
                <?php endforeach; ?>
            </td>
        </tr>

        <!-- Database Config Row -->
        <tr>
            <th class="left-column">Database Configuration</th>
            <td class="right-column">
                <div><strong>Database Host:</strong> <?php echo htmlspecialchars($db_config['Database Host']); ?></div>
                <div><strong>Database Name:</strong> <?php echo htmlspecialchars($db_config['Database Name']); ?></div>
                <div><strong>Database User:</strong> <?php echo htmlspecialchars($db_config['Database User']); ?></div>
            </td>
        </tr>
    </table>
</body>
</html>
