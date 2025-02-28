<?php
session_start();

// Deny access if not admin
if (!isset($_SESSION['accessLevel']) || $_SESSION['accessLevel'] !== 'admin') {
    http_response_code(403);
    echo "Access Denied!";
    exit();
}

require_once 'utilities/db_connection.php';

//manager query

$managerQuery = $pdo->prepare("
SELECT
  FLOOR(SUM(TIMESTAMPDIFF(MONTH, start_date, end_date)) / 12) AS total_years,
  MOD(SUM(TIMESTAMPDIFF(MONTH, start_date, end_date)), 12) AS total_months
FROM Job_history
WHERE LOWER(job_title) LIKE '%manager%';
");



// Query to get job gaps between companies
$gapQuery = $pdo->prepare("
    WITH job_history_with_prev AS (
        SELECT 
            jh.company_id,
            c.company_name,
            jh.start_date,
            jh.end_date,
            LAG(jh.end_date) OVER (ORDER BY jh.start_date) AS prev_end_date,
            LAG(c.company_name) OVER (ORDER BY jh.start_date) AS prev_company
        FROM Job_history jh
        LEFT JOIN companies c ON jh.company_id = c.company_id
    )
    SELECT 
        CONCAT(prev_company, ' and ', company_name) AS company_gap,
        TIMESTAMPDIFF(MONTH, prev_end_date, start_date) AS gap_months
    FROM job_history_with_prev
    WHERE prev_company <> company_name
    AND TIMESTAMPDIFF(MONTH, prev_end_date, start_date) > 0
    ORDER BY prev_end_date;
");

$jobcount = $pdo->query("SELECT COUNT(*) FROM Job_history")->fetchColumn();

// Query to get total job history
$jobdata = $pdo->prepare("
    SELECT
        COALESCE(c.company_name, 'Unknown Company') AS company_name,
        jh.job_title,
        SUM(
            (YEAR(jh.end_date) - YEAR(jh.start_date)) * 12 +
            (MONTH(jh.end_date) - MONTH(jh.start_date)) +
            CASE WHEN DAY(jh.end_date) >= DAY(jh.start_date) THEN 0 ELSE -1 END
        ) AS total_months,
        FLOOR(SUM(
            (YEAR(jh.end_date) - YEAR(jh.start_date)) * 12 +
            (MONTH(jh.end_date) - MONTH(jh.start_date)) +
            CASE WHEN DAY(jh.end_date) >= DAY(jh.start_date) THEN 0 ELSE -1 END
        ) / 12) AS total_years,
        MOD(SUM(
            (YEAR(jh.end_date) - YEAR(jh.start_date)) * 12 +
            (MONTH(jh.end_date) - MONTH(jh.start_date)) +
            CASE WHEN DAY(jh.end_date) >= DAY(jh.start_date) THEN 0 ELSE -1 END
        ), 12) AS total_remaining_months,
        MAX(jh.end_date) AS most_recent_end_date
    FROM Job_history jh
    LEFT JOIN companies c ON jh.company_id = c.company_id
    GROUP BY c.company_name, jh.job_title
    ORDER BY most_recent_end_date DESC;
");

$managerQuery->execute();
$manager = $managerQuery->fetch(PDO::FETCH_ASSOC); // Fetch a single row

$gapQuery->execute();
$gaps = $gapQuery->fetchAll(PDO::FETCH_ASSOC);

$jobdata->execute();
$results = $jobdata->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Statistics</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #F5F5F5;
            font-family: Source Code Pro, Arial;
            margin: 30px;
        }
        .container {
            max-width:80%;
            margin: 2px auto;
            padding: 2px;
            background-color: #c3d0d9;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }
        tr {
            color: black;
            padding: 2px;
            border-radius: 1px;
        }
        h4 {
            background-color: dodgerblue;
            color: white;
            padding: 2px;
            border-radius: 5px;
            text-align: center;
        }
        h3 {
            background-color: dodgerblue;
            color: white;
            padding: 2px;
            border-radius: 5px;
            text-align: center;
        }
        h2 {
            background-color: black;
            color: white;
            padding: 2px;
            border-radius: 5px;
            text-align: center;
        }
        h1 {
            background-color: darkblue;
            color: white;
            padding: 2px;
            border-radius: 5px;
            text-align: center;
        }
        .table {
            width: 100%;
            margin-top: 5px;
        }
        .table th {
	font-family: Source Code Pro, Arial;
         background:  #c3d0d9;   
	 background-color: #343a40;
         color: white;
         text-align: center;
	 padding: 5px;
        }
        .job {
            font-family: Source Code Pro, Arial;
            font-weight: bold;
            color: darkblue;
            padding: 0px;
            border-radius: 1px;
        }
        .company-total {
	    font-family: Source Code Pro, Arial;
            font-weight: bold;
	    font-size: small;
            color: white;
            background-color: #000;
            text-align: right;
	    padding: 5px;
         border-radius: 5px;
        }
        .company {
	    font-family: Source Code Pro, Arial;
            font-weight: bold;
	    font-size: small;
            color: white;
            background-color: #000;
            text-align: right;
	    padding: 5px;
         border-radius: 5px;
        }

	tbody {
	font-family: Source Code Pro, Arial;
	padding: 2px;
	}
    </style>
</head>
<body>
    <div class="container">
        <h1>Career Statistics</h1>

        <!-- Job Count -->
        <div class="text-center my-4">
            <h5><strong>Number of Jobs:</strong> <?php echo $jobcount; ?></h5>
        </div>

        <!-- Employment Gaps -->
        <h4>Gaps in Employment</h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Company Transition</th>
                    <th>Gap Duration</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gaps as $gap): ?>
                     <tr>
                       <td style="font-weight: bold;"><?php echo htmlspecialchars($gap['company_gap']); ?></td>
                        <td><?php echo htmlspecialchars($gap['gap_months']) . ' months'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Job History -->
        <h4>Job History</h4>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Job Title</th>
                    <th>Years</th>
                    <th>Months</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $totalCareerMonths = 0;
                    $currentCompany = null;
                    $companyTotalMonths = 0;

                    foreach ($results as $result):
                        if ($currentCompany !== $result['company_name'] && $currentCompany !== null) {
                            // Display company total row
                            echo '<tr class="company-total">';
                            echo '<td class="company" colspan="2">' . htmlspecialchars($currentCompany) . ' Total</td>';
                            echo '<td style="background-color:#5d636d;">' . floor($companyTotalMonths / 12) . ' years</td>';
                            echo '<td style="background-color:#7d8697;">' . ($companyTotalMonths % 12) . ' months</td>';
                            echo '</tr>';

                            $companyTotalMonths = 0;
                        }

                        $companyTotalMonths += $result['total_months'];
                        $currentCompany = $result['company_name'];
                ?>
                    <tr>
                        <td class="job"><?php echo htmlspecialchars($result['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($result['job_title']); ?></td>
                        <td style="text-align: center;"><?php echo htmlspecialchars($result['total_years']); ?></td>
                        <td style="text-align: center;"><?php echo htmlspecialchars($result['total_remaining_months']); ?></td>
                    </tr>
                <?php
                    $totalCareerMonths += $result['total_months'];
                    endforeach;

                    // Last company total
                    if ($currentCompany !== null) {
                        echo '<tr class="company-total">';
                        echo '<td colspan="2">' . htmlspecialchars($currentCompany) . ' Total</td>';
                        echo '<td>' . floor($companyTotalMonths / 12) . ' years</td>';
                        echo '<td>' . ($companyTotalMonths % 12) . ' months</td>';
                        echo '</tr>';
                    }

                    $totalYears = floor($totalCareerMonths / 12);
                    $totalMonths = $totalCareerMonths % 12;
                ?>
            </tbody>
        </table>

        <!-- Career Summary -->
        <div class="text-center mt-4">
       <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Experience Type </th>
                    <th>Years Experience</th>
                </tr>
            </thead>
            <tr><td style="color:dodgerblue; font-weight: bold;">Overall Experience: </td>
		<td><?php echo $totalYears; ?> years, <?php echo $totalMonths; ?> months</td></tr>
       		<tr>
		<td style="color:dodgerblue;font-weight: bold;">Management Experience:</td>
 		<td style="color:black;"><?php echo htmlspecialchars($manager['total_years']); ?> years, <?php echo htmlspecialchars($manager['total_months']); ?> months</td>
		</tr>
        </div>
    </div>
</body>
</html>
