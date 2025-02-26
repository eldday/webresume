<?php
require_once 'utilities/db_connection.php';

$jobdata = $pdo->prepare("
  SELECT
    COALESCE(c.company_name, 'Unknown Company') AS company_name,
    jh.job_title,
    SUM(
      (YEAR(jh.end_date) - YEAR(jh.start_date)) * 12 + (MONTH(jh.end_date) - MONTH(jh.start_date)) +
      CASE WHEN DAY(jh.end_date) >= DAY(jh.start_date) THEN 0 ELSE -1 END
    ) AS total_months,
    CASE
      WHEN SUM(
        (YEAR(jh.end_date) - YEAR(jh.start_date)) * 12 + (MONTH(jh.end_date) - MONTH(jh.start_date)) +
        CASE WHEN DAY(jh.end_date) >= DAY(jh.start_date) THEN 0 ELSE -1 END
      ) < 12 THEN 0
      ELSE FLOOR(
        SUM(
          (YEAR(jh.end_date) - YEAR(jh.start_date)) * 12 + (MONTH(jh.end_date) - MONTH(jh.start_date)) +
          CASE WHEN DAY(jh.end_date) >= DAY(jh.start_date) THEN 0 ELSE -1 END
        ) / 12
      )
    END AS total_years,
    MOD(
      SUM(
        (YEAR(jh.end_date) - YEAR(jh.start_date)) * 12 + (MONTH(jh.end_date) - MONTH(jh.start_date)) +
        CASE WHEN DAY(jh.end_date) >= DAY(jh.start_date) THEN 0 ELSE -1 END
      ),
      12
    ) AS total_remaining_months,
    MAX(jh.end_date) AS most_recent_end_date
  FROM
    Job_history jh
  LEFT JOIN
    companies c ON jh.company_id = c.company_id
  GROUP BY
    c.company_name, jh.job_title
  ORDER BY most_recent_end_date DESC;
");

// Execute the query and fetch results
$jobdata->execute();
$results = $jobdata->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Job Data</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #DDDDDD;
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 20px;
      background-color: #f8f9fa;
      border: 0px solid #000;
      border-radius: 10px;
      box-shadow: 1px 1px 10px rgba(0, 0, 0, 2);
    }
    .table th, .table td {
      vertical-align: middle;
      padding: 8px 12px;
    }
    .company-total {
      font-weight: bold;
      color: #FFF;
      background-color: #666;
      text-align: right;
    }
    .text-right {
      text-align: right;
    }
    .company-total-label {
      border: 0px solid #DDD;
      text-align: left;
      padding-left: 12px;
    }
    .table {
      width: 100%;
      table-layout: auto;
    }
    .table td, .table th{
      white-space: nowrap;
    }
    .manager-column {
      display: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2 style="background-color: black; color: white; font-weight: bold;" class="mb-4">Job History Totals</h2>
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead class="thead-light">
          <tr>
            <th>Company</th>
            <th>Job Title</th>
            <th>Years</th>
            <th>Months</th>
            <th class="manager-column">Manager Years</th>
            <th class="manager-column">Manager Months</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $totalCareerMonths = 0;
            $totalManagerCareerMonths = 0;
            $currentCompany = null;
            $companyTotalMonths = 0;

            foreach ($results as $result):
              if ($currentCompany !== $result['company_name'] && $currentCompany !== null) {
                // Display company total row
                $companyTotalYears = floor($companyTotalMonths / 12);
                $companyTotalRemainingMonths = $companyTotalMonths % 12;

                echo '<tr class="company-total">';
                echo '<td colspan="3" class="company-total-label">' . htmlspecialchars($currentCompany) . ' Total</td>';
                echo '<td>' . $companyTotalYears . ' years</td>';
                echo '<td>' . $companyTotalRemainingMonths . ' months</td>';
                echo '<td class="manager-column"></td>';
                echo '</tr>';

                $companyTotalMonths = 0;
              }

              $companyTotalMonths += $result['total_months'];
              $currentCompany = $result['company_name'];
          ?>
              <tr>
                <td><?php echo htmlspecialchars($result['company_name']); ?></td>
                <td><?php echo htmlspecialchars($result['job_title']); ?></td>
                <td class="text-right"><?php echo htmlspecialchars($result['total_years']); ?></td>
                <td class="text-right"><?php echo htmlspecialchars($result['total_remaining_months']); ?></td>
                <td class="text-right manager-column">
                  <?php
                    if (strpos(strtolower($result['job_title']), 'manager') !== false) {
                      echo floor($result['total_months'] / 12);
                      $totalManagerCareerMonths += $result['total_months'];
                    } else {
                      echo 'N/A';
                    }
                  ?>
                </td>
                <td class="text-right manager-column">
                  <?php
                    if (strpos(strtolower($result['job_title']), 'manager') !== false) {
                      echo $result['total_months'] % 12;
                    } else {
                      echo 'N/A';
                    }
                  ?>
                </td>
              </tr>
          <?php
              $totalCareerMonths += $result['total_months'];
            endforeach;

            if ($currentCompany !== null) {
              $companyTotalYears = floor($companyTotalMonths / 12);
              $companyTotalRemainingMonths = $companyTotalMonths % 12;

              echo '<tr class="company-total">';
              echo '<td colspan="3" class="company-total-label">' . htmlspecialchars($currentCompany) . ' Total</td>';
              echo '<td>' . $companyTotalYears . ' years</td>';
              echo '<td>' . $companyTotalRemainingMonths . ' months</td>';
              echo '<td class="manager-column"></td>';
              echo '</tr>';
            }

            $totalYears = floor($totalCareerMonths / 12);
            $totalMonths = $totalCareerMonths % 12;

            $totalManagerYears = floor($totalManagerCareerMonths / 12);
            $totalManagerMonths = $totalManagerCareerMonths % 12;
          ?>
        </tbody>
      </table>
    </div>
   <h4> <p class="mt-3">
       <strong> <span style="color:blue;">Career total:</strong></span>
        <span style="color:black;"> <?php echo $totalYears; ?> years, <?php echo $totalMonths; ?> months</span>
    </p></h4>
  <h4>  <p class="mt-3">
        <strong><span style="color:blue;">Management experience:</strong></span>
        <span style="color:black;"><?php echo $totalManagerYears; ?> years, <?php echo $totalManagerMonths; ?> months</span>
    </p></h4
  </div>
</body>
</html>