<?php
session_start();

// Update these settings if your NJIT MySQL connection uses different credentials.
$dbHost = 'localhost';
$dbUser = 'he83';
$dbPass = '1072ReeveS@@';
$dbName = 'he83';

$error = '';
$studentId = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = trim($_POST['student_id'] ?? '');

    if ($studentId === '') {
        $error = 'Please enter a student ID.';
    } else {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
            $db->set_charset('utf8mb4');

            $stmt = $db->prepare('SELECT ID FROM Student WHERE ID = ? LIMIT 1');
            $stmt->bind_param('s', $studentId);
            $stmt->execute();
            $stmt->store_result();
            $found = $stmt->num_rows > 0;
            $stmt->close();
            $db->close();

            if ($found) {
                session_regenerate_id(true);
                $_SESSION['student_id'] = $studentId;
                header('Location: student_details.php');
                exit;
            }
            unset($_SESSION['student_id']);
            $error = 'Student ID cannot be found.';
        } catch (mysqli_sql_exception $e) {
            error_log('Week 10 database error: ' . $e->getMessage());
            $error = 'Unable to connect to the student database. Check the database settings.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Week 10 - Student Search</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; background: #eef2f7; color: #223047; }
    .card { max-width: 480px; margin: 10vh auto; padding: 32px; background: white; border-radius: 12px; box-shadow: 0 6px 22px #0001; }
    h1 { font-size: 26px; margin: 0 0 10px; }
    p { color: #59687b; }
    label { display: block; margin: 22px 0 8px; font-weight: bold; }
    input { box-sizing: border-box; width: 100%; padding: 12px; font-size: 16px; border: 1px solid #9caabc; border-radius: 6px; }
    button { width: 100%; margin-top: 20px; padding: 13px; background: #1767b2; color: white; border: 0; border-radius: 6px; font-size: 16px; cursor: pointer; }
    button:hover { background: #104d88; }
    .error { color: #a51b1b; font-weight: bold; margin-top: 14px; }
  </style>
</head>
<body>
  <main class="card">
    <h1>Student Information Lookup</h1>
    <p>Enter a student ID to view the student's information and transcript.</p>
    <form method="post" action="index.php">
      <label for="student_id">Student ID</label>
      <input id="student_id" name="student_id" type="text" value="<?= htmlspecialchars($studentId, ENT_QUOTES, 'UTF-8') ?>" required autofocus>
      <button type="submit">Find Student</button>
    </form>
    <?php if ($error !== ''): ?>
      <p class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
      <script>alert(<?= json_encode($error, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);</script>
    <?php endif; ?>
  </main>
</body>
</html>
