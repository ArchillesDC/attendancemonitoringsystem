<?php
include '../connection.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['pass'];

  // Use prepared statements to prevent SQL injection
  $stmt = $conn->prepare("SELECT * FROM manageuser WHERE username=? AND password=?");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['name'] = $row['fullname']; // Store the fullname in the session
    echo json_encode(['success' => true]);
    exit();
  } else {
    echo json_encode(['success' => false]);
  }

  $stmt->close();
}
?>
