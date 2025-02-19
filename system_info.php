<?php
session_start();
require_once 'utilities/db_connection.php'; // Include database connection

// Get PDO object from db_connection.php
// Assuming $pdo is set correctly in db_connection.php

// Fetch database configuration details (manually set these if not in db_connection.php)
$db_config = [
    "Database Host" => 'localhost',  // Adjust as needed
    "Database Name" => 'resume',     // Adjust as needed
    "Database User" => 'pday'        // Adjust as needed
];

// Fetch system details
$system_info = [
    "Hostname" => gethostname(),
    "Operating System" => php_uname(),
    "Server IP" => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
    "Client IP" => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
    "PHP Version" => phpversion(),
    "Server Software" => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    "Current User" => get_current_user()
];

// Fetch networking details
$network_info = [
    "Domain Name" => trim(shell_exec('hostname -d 2>/dev/null')) ?: "Unknown",
    "Local IP Address" => trim(shell_exec("hostname -I | awk '{print $1}'")) ?: $_SERVER['SERVER_ADDR'] ?? "Unknown",
    "Gateway IP" => trim(shell_exec("ip route | grep default | awk '{print $3}'")) ?: "Unknown",
    "DNS Servers" => trim(shell_exec("nmcli dev show | grep 'IP4.DNS' | awk '{print $2}' | paste -sd ', ' -")) ?: "Unknown",
];

// Fetch public IP (Requires internet connection)
$public_ip = trim(file_get_contents("http://ifconfig.me")) ?: "Unknown";
$network_info["Public IP Address"] = $public_ip;
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
        textarea {
          display: none; /* Hide textarea initially, as it's replaced by CKEditor */
        }

        body {
            display: flex;
            font-family: Arial, sans-serif;
            border-right: 4px solid #ccc;
        }
        .details {
            width: 80%;
            padding: 20px;
            margint-right: 20px;
            margin-left: 20px;
        }
        .sidebar {
            width: 20%;
            padding: 20px;
            border-right: 4px solid #ccc;
            border-left: 4px solid #ccc;
        }
        .main {
            flex-grow: 1;
            padding: 5px;
        }
        .user-list {
            list-style: none;
            padding: 5;
        }
        .user-list li {
            margin-bottom: 5px;
            background-color: rgba(0, 0, 0, 0.059);
            border: solid #ffffff1f 0.75pt;
            border-width: 15 15 1px 0; /* Bottom border only */
            line-height: 1.2;
            margin-bottom: 5px;
            padding: 6pt 4pt;
            text-indent: 0;
            font-size: 16px;
            color: #333;
        }
        .list {
            width: 25%;
            border-right: 2px solid #ccc;
            border-left: 2px solid #ccc;
            padding: 10px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin: 5px 0;
        }
        textarea {
            display: block; /* Ensure the textarea is visible initially */
           margin: 10px;
       }
    </style>

</head>
<body style="background-color: #a9b8c2;">
<style>
   .modal-body {
	width: 100%;
	vertical-align: top;	
	align: center;
}
    table {
        border-collapse: collapse;
        border: 1px solid rgb(140, 140, 140);
        font-size: 1.0rem;
        width: 95%;
        margin: 1px auto;
        border-radius: 10px;
        box-shadow: 1px 1px 10px rgba(0, 0, 0, 2);
        width: 95%;
        align: center;
    }
    th, td {
        border: 1px solid rgb(160, 160, 160);
        padding: 2px 2px;
        text-align: left;
 	border-radius: 5px;
    }
    th {
        background-color: #333;
        color: #09D;
	text-align: top;
    }
    td {
 vertical-align: top;
        background-color: #fff;
    }
    h3 {
        color: #333;
    }
    .right-column {
        width: 70%; /* Make sure the right column is larger */
        background-color:  #d0d2d3;
	font-family: 'Arial';
	font-size: .9rem;
}
    .left-column {
        width: 30%;
	font-family: 'Arial';
	font-size: 1.0rem;
	text-align: right;
	vertical-align: top;
 /* System info column width */
    }
    .header {
	background-color: #444;
        width: 100%;
        font-size: 1.3rem;
        text-align: center;
        vertical-align: center;
 /* System info column width */
    }
</style>

<center>
    <table>
        <!-- System Info Row -->
        
	<th colspan="2" class="header">Admin Modal</th>
	</tr>
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
                <?php foreach ($db_config as $key => $value): ?>
                    <div><strong><?php echo htmlspecialchars($key); ?>:</strong> <?php echo htmlspecialchars($value); ?></div>
                <?php endforeach; ?>
            </td>
        </tr>
    </table>
</center>

</body>
</html>
