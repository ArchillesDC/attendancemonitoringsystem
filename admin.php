<?php
include '../connection.php';

if (!isset($_SESSION['name'])) {
  // Redirect to the login page if the user is not logged in
  header('Location: Loginpage.php');
  exit();
}

$fullName = $_SESSION['name'];

// Assuming you update the name in manageuser.php
// After updating the name, set the session variable
// $_SESSION['name'] = $newName; // Uncomment and replace $newName with the actual updated name
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin</title>
  <link rel="stylesheet" href="../css/index.css" />
  <link rel="icon" href="../images/icon.jpg">
</head>

<body>
  <header>
    <div class='welcome-message'>Welcome: <?php echo $fullName; ?></div>
    <nav>
      <a href="manageuser.php">Manage User</a>
      <a href="manageemployee.php">Manage Employee</a>
      <a href="../admin/attendancetable.php">Attendance Table</a>
      <a href="../public.php">Log Out</a>
    </nav>
  </header>
  <h1>CAFÉ CERVEZA</h1>
  <h3>Satisfy your cravings</h3>
  <footer>
    <a href="https://www.facebook.com/cafecervezatupi" class="fb"><img src="../images/facebook.png" alt="">Cafe Cerveza</a>
    <a href="https://www.instagram.com/cafecervezatupi/" class="ig"><img src="../images/instagram.png" alt="">Cafe Cerveza</a>
    <a href="#" class="call"><img src="../images/phone-call.png" alt="">Cafe Cerveza</a>
  </footer>

</body>

</html>
