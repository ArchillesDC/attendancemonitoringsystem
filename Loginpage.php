<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Design by foolishdeveloper.com -->
  <title>Login Page</title>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link rel="stylesheet" href="../css/fontawesome.css6">
  <link href="../css/poppins.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/loginpage.css">
  <link rel="icon" href="images/icon.jpg">

  <!-- Stylesheet -->
  <style>
    /* Add CSS for loading animation */
    #loading {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(255, 255, 255, 0.8);
      padding: 20px;
      border-radius: 10px;
      z-index: 9999;
    }

    #loading-text {
      font-size: 24px;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const usernameInput = document.getElementById('username');
      const passwordInput = document.getElementById('password');

      usernameInput.addEventListener('input', function() {
        validateInput(usernameInput, 4);
      });

      passwordInput.addEventListener('input', function() {
        validateInput(passwordInput, 4);
      });

      function validateInput(inputElement, minLength) {
        const inputValue = inputElement.value.trim();
        const isInvalid = inputValue.length <= minLength;

        if (isInvalid) {
          inputElement.style.borderColor = 'red';
        } else {
          inputElement.style.borderColor = 'green';
        }
      }
    });
  </script>
</head>

<body>
  <div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
  </div>
  <header>
    <div class="logo"><a href="../public.php"><img src="../images/6.jpg" alt=""></a></div>
    <nav class="active">
      <a href="../public/Timein.php">Time In</a>
      <a href="../public/timeout.php">Time Out</a>
      <a href="../public/attendancetable.php">Attendance Table</a>
    </nav>
  </header>
  <form method="post" id="loginform">
    <h3>Login Here</h3>
    <label for="username">Username</label>
    <input type="text" placeholder="Email or Phone" name="username" id="username" required>
    <label for="password">Password</label>
    <input type="password" placeholder="Password" name="pass" id="password" required>
    <p>admin login page only</p>
    <button name="login" onclick="showLoading()">Log In</button>
  </form>

  <!-- Loading div with animation -->
  <div id="loading">
    <div id="loading-text">Loading <span id="loading-progress">0</span>%</div>
  </div>

  <script>
    function showLoading() {
      document.getElementById('loading').style.display = 'flex';
      simulateLoading();
    }

    function simulateLoading() {
      let progress = 0;
      const loadingText = document.getElementById('loading-text');
      const loadingProgress = document.getElementById('loading-progress');

      function updateProgress() {
        loadingProgress.textContent = progress;
        if (progress < 500) {
          setTimeout(updateProgress, 1000);
        } else {
          // Hide loading div after loading completes
          document.getElementById('loading').style.display = 'flex';
          // Show alert
          alert('Database connected');
        }
        progress++;
      }

      updateProgress();
    }
  </script>
</body>

</html>

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
    $_SESSION['name'] = $row['fullname'];
    echo "<script>window.location = 'admin.php';</script>";
    exit();
  } else {
    echo "<script>
            alert('Invalid Username Password');
            window.location = 'Loginpage.php';
          </script>";
  }

  $stmt->close();
}
?>