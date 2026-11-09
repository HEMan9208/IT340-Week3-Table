<?php
session_start();

// Update these settings to match the settings in index.php.
$dbHost = 'localhost';
$dbUser = 'he83';
$dbPass = 'REPLACE_WITH_YOUR_MYSQL_PASSWORD';
$dbName = 'he83';

if (!isset($_SESSION['student_id'])) {
    header('Location: index.php');
    exit;
}
$studentId = (string) $_SESSION['student_id'];
$rows = [];
$error = '';

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    $db->set_charset('utf8mb4');

    // The INNER JOIN is restricted to the one ID saved in the session.
    $sql = 'SELECT Student.Name, Student.ID, Student.Major, Transcript.Course, Transcript.Grade
            FROM Student
            INNER JOIN Transcript ON Student.ID = Transcript.ID
            WHERE Student.ID = ?
            ORDER BY Transcript.Course';
    $stmt = $db->prepare($sql);
    $stmt->bind_param('s', $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
    $db->close();
} catch (mysqli_sql_exception $e) {
    error_log('Week 10 database error: ' . $e->getMessage());
    $error = 'Unable to load the student details. Check the database settings.';
}

function h($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Week 10 - Student Details</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #eef2f7; color: #223047; }
    .card { max-width: 860px; margin: 7vh auto; padding: 32px; background: white; border-radius: 12px; box-shadow: 0 6px 22px #0001; }
    h1 { margin-top: 0; }
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; margin: 24px 0; }
    th, td { padding: 13px; border: 1px solid #d0d9e4; text-align: left; }
    th { background: #1767b2; color: white; }
    tr:nth-child(even) { background: #f4f7fb; }
    .home { display: inline-block; padding: 12px 20px; background: #1767b2; color: white; border-radius: 6px; text-decoration: none; }
    .home:hover { background: #104d88; }
    .error { color: #a51b1b; }
  </style>
</head>
<body>
  <main class="card">
    <h1>Student Information</h1>
    <p>Student ID: <strong><?= h($studentId) ?></strong></p>
    <?php if ($error !== ''): ?>
      <p class="error" role="alert"><?= h($error) ?></p>
    <?php elseif (count($rows) === 0): ?>
      <p>No transcript records were found for this student.</p>
    <?php else: ?>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Name</th><th>Student ID</th><th>Major</th><th>Course</th><th>Grade</th></tr></thead>
          <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= h($row['Name']) ?></td>
              <td><?= h($row['ID']) ?></td>
              <td><?= h($row['Major']) ?></td>
              <td><?= h($row['Course']) ?></td>
              <td><?= h($row['Grade']) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
    <a class="home" href="index.php">Home / Back</a>
  </main>
</body>
</html>
