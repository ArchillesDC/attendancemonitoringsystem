<?php
//DONE
include '../connection.php';

// Check if 'updateuser' key is set in $_GET
$id = isset($_GET['updateuser']) ? (int)$_GET['updateuser'] : 0;

if ($id > 0) {
    $sql = "SELECT * FROM manageuser WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get the result
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the row
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $name = $row['fullname'];
        $phonenumber = $row['phonenumber'];
        $username = $row['username'];
        $password = $row['password'];

        // Move the code here to fetch the result after ensuring $row is not null
        // ...

        if (isset($_POST['update'])) {
            $name = $_POST['fullname'];
            $phonenumber = $_POST['phonenumber'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            if (empty($name) || empty($phonenumber) || empty($username) || empty($password)) {
                echo '<script>alert("Please Fill out this form");</script>';
            } else {
                $sql = "UPDATE manageuser SET fullname=?, phonenumber=?, username=?, password=? WHERE id=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ssssi", $name, $phonenumber, $username, $password, $id);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    echo '<script>alert("Update user successfully"); window.location = "manageuser.php";</script>';
                    exit();
                } else {
                    echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
                    exit();
                }
            }
        }
    } else {
        // Handle the case where the user with the given ID is not found
        echo '<script>alert("User not found.");</script>';
        exit();
    }
} else {
    // Handle the case where 'updateuser' key is not set or invalid
    echo '<script>alert("Invalid user ID.");</script>';
    exit();
}
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
    <!-- Stylesheet -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fullnameInput = document.getElementById('fullname');
            const phonenumberInput = document.getElementById('phonenumber');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const passwordStrengthMessage = document.getElementById('password-strength-message');

            fullnameInput.addEventListener('input', function() {
                validateInput(fullnameInput, 5);
            });

            phonenumberInput.addEventListener('input', function() {
                validateInput(phonenumberInput, 11);
            });

            usernameInput.addEventListener('input', function() {
                validateInput(usernameInput, 4);
            });

            passwordInput.addEventListener('input', function() {
                validateInput(passwordInput, 4);
                checkPasswordStrength();
            });

            function validateInput(inputElement, minLength) {
                const inputValue = inputElement.value.trim();
                const isInvalid = inputValue.length < minLength;

                if (isInvalid) {
                    inputElement.style.borderColor = 'red';
                } else {
                    inputElement.style.borderColor = '';
                }
            }

        });

        document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordStrengthMessage = document.getElementById('password-strength-message');

        passwordInput.addEventListener('input', function() {
          checkPasswordStrength();
        });

        function checkPasswordStrength() {
          const password = passwordInput.value;

          // Define your password strength criteria
          const minLength = 8;
          const hasUppercase = /[A-Z]/.test(password);
          const hasLowercase = /[a-z]/.test(password);
          const hasDigits = /\d/.test(password);
          const hasSpecialChars = /[!@#$%^&*(),.?":{}|<>]/.test(password);

          // Calculate the strength score based on criteria
          let strengthScore = 0;
          if (password.length >= minLength) strengthScore++;
          if (hasUppercase) strengthScore++;
          if (hasLowercase) strengthScore++;
          if (hasDigits) strengthScore++;
          if (hasSpecialChars) strengthScore++;

          // Update the UI based on the strength score
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
        <a href="manageuser.php" class="1"><img src="../images/arrow.png" alt=""></a>
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
                        <a href="updateuser.php?updateuser=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                        <a href="deleteuser.php?deleteuser=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-trash fs-5 me-3"></i></a>
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
        <input type="text" placeholder="Archilles Dela Cruz" name="fullname" id="fullname" autocomplete="off" value="<?php echo $name; ?>" required>

        <label for="username">Phone Number</label>
        <input type="number" placeholder="09xxxxxxxxx" name="phonenumber" id="phonenumber" autocomplete="off" value="<?php echo $phonenumber; ?>" required>

        <label for="username">Username</label>
        <input type="text" placeholder="Email or Phone" name="username" id="username" autocomplete="off" value="<?php echo $username; ?>" required>

        <label for="password">Password</label>
        <div class="password-container">
            <input type="password" placeholder="Password" name="password" id="password" autocomplete="off" value="<?php echo $password; ?>" required>
        </div>
        <div id="password-strength-message"></div>

        <div class="btn">
            <button name="update">Update</button>
        </div>

    </form>
    <script>
        function toggleMenu() {
            document.querySelector('header').classList.toggle('responsive');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const fullnameInput = document.getElementById('fullname');

            fullnameInput.addEventListener('input', function() {
                validateFullName(fullnameInput);
            });

            function validateFullName(inputElement) {
                const inputValue = inputElement.value.trim();
                const isValid = /^[a-zA-Z\s]*$/.test(inputValue);

                if (!isValid) {
                    inputElement.value = inputValue.replace(/[^a-zA-Z\s]/g, '');
                    inputElement.style.borderColor = 'red';
                    inputElement.setCustomValidity('Please enter only letters');
                } else {
                    inputElement.style.borderColor = '';
                    inputElement.setCustomValidity('');
                }
            }
        });

    </script>
</body>

</html>