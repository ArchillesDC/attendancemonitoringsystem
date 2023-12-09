<?php
include '../connection.php';

$sql = "SELECT * FROM `manageuser`";
$result = mysqli_query($conn, $sql);

if (!$result) {
  echo "Error: " . mysqli_error($conn);
} else {
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/fontawesome.css">
    <link href="../css/poppins.css" rel="stylesheet">
    <link href="../css/boostrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/fontawesome6.css"/>
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/manage.css">
    <link rel="icon" href="../images/icon.jpg">

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordStrengthMessage = document.getElementById('password-strength-message');

        passwordInput.addEventListener('input', function() {
          checkPasswordStrength();
        });

        function checkPasswordStrength() {
          const password = passwordInput.value;
          const minLength = 8;
          const hasUppercase = /[A-Z]/.test(password);
          const hasLowercase = /[a-z]/.test(password);
          const hasDigits = /\d/.test(password);
          const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);

          let strengthScore = 0;
          if (password.length >= minLength) strengthScore++;
          if (hasUppercase) strengthScore++;
          if (hasLowercase) strengthScore++;
          if (hasDigits) strengthScore++;
          if (hasSpecialChars) strengthScore++;

          if (strengthScore === 0) {
            passwordStrengthMessage.innerHTML = '';
          } else if (strengthScore < 3) {
            passwordStrengthMessage.innerHTML = '<span style="color: red;">Weak password</span>';
          } else if (strengthScore < 5) {
            passwordStrengthMessage.innerHTML = '<span style="color: orange;">Moderate password</span>';
          } else {
            passwordStrengthMessage.innerHTML = '<span style="color: green;">Strong password</span>';
          }
        }
      });

    </script>
  </head>

  <body class="body1">
    <header>
      <a href="admin.php">
        <img src="../images/arrow.png" style="width: 40px; margin-right: 30rem;">
      </a>
      <div class="burger-icon" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
      </div>
    </header>

    <div class="background">
      <div class="shape"></div>
      <div class="shape"></div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>Phone Number</th>
          <th>Username</th>
          <th>Password</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo $row['fullname'] ?></td>
            <td><?php echo $row['phonenumber'] ?></td>
            <td><?php echo $row['username'] ?></td>
            <td><?php echo str_repeat('•', strlen($row['password'])); ?></td>
            <td>
              <a href="updateuser.php?updateuser=<?php echo $row['id']; ?>" class="link-dark"><img src="../images/editing.png" alt=""></a>
              <a href="deleteuser.php?deleteuser=<?php echo $row['id']; ?>" class="link-dark"><img src="../images/bin.png" alt=""></a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>

    <form method="post" id="loginform">
      <h3>Manage User</h3>
      <label for="username">Full Name</label>
      <input type="text" placeholder="Archilles Dela Cruz" name="fullname" id="fullname" required>

      <label for="username">Phone Number</label>
      <input type="number" placeholder="09xxxxxxxxx" name="phonenumber" id="phonenumber" required>

      <label for="username">Username</label>
      <input type="text" placeholder="Email or Phone" name="username" id="username" required>

      <label for="password">Password</label>
      <div class="password-container">
        <input type="password" placeholder="Password" name="pass" id="password" oninput="checkPasswordStrength()" required>
      </div>

      <div id="password-strength-message"></div>

      <div class="btn">
        <center>
          <button name="login">Sign Up</button>
        </center>
      </div>
    </form>

    <script>
      function toggleMenu() {
        document.querySelector('header').classList.toggle('responsive');
      }
    </script>

    <?php
    if (isset($_POST['login'])) {
      $fullname = $_POST['fullname'];
      $phonenumber = $_POST['phonenumber'];
      $username = $_POST['username'];
      $password = $_POST['pass'];

      if (strlen($fullname) <= 5) {
        echo "<script>alert('Name must be longer than 5 characters');</script>";
        exit();
      }

      if (!preg_match('/^(09|\+639)[0-9]{9}$/', $phonenumber)) {
        echo "<script>alert('Invalid phone number format');</script>";
        exit();
      }

      if (strlen($username) <= 3) {
        echo "<script>alert('Username must be longer than 3 characters');</script>";
        exit();
      }

      if (strlen($password) <= 5) {
        echo "<script>alert('Password must be longer than 5 characters');</script>";
        exit();
      }

      $checkQuery = "SELECT * FROM `manageuser` WHERE fullname = '$fullname'";
      $checkResult = mysqli_query($conn, $checkQuery);

      if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('Full Name already exists. Please choose a different one.');</script>";
        exit();
      }

      $sql = "INSERT INTO manageuser (fullname, phonenumber, username, password) VALUES ('$fullname', '$phonenumber', '$username', '$password')";

      if ($conn->query($sql) === TRUE) {
        echo "<script>
                  alert('Register successfully');
                  window.location = 'manageuser.php';
                </script>";
        exit();
      } else {
        echo "<div style='color: red;'><strong>Error:</strong> " . $sql . "<br>" . $conn->error . "</div>";
      }
    }

    $conn->close();
    ?>

  <?php
}
  ?>
  </body>

  </html>