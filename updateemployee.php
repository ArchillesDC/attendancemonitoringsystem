<?php
//DONEEEEE
include '../connection.php';
include 'phpqrcode/qrlib.php';

// Check if 'updateuser' key is set in $_GET
$id = isset($_GET['updateemployee']) ? (int)$_GET['updateemployee'] : 0;

if ($id > 0) {
    $sql = "SELECT * FROM manageemployee WHERE id=?";
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
        $address = $row['address'];

        // Move the code here to fetch the result after ensuring $row is not null
        // ...

        if (isset($_POST['update'])) {
            $name = $_POST['fullname'];
            $phonenumber = $_POST['phonenumber'];
            $address = $_POST['address'];

            // Validate phone number
            if (!preg_match("/^(09|\+639)\d{9}$/", $phonenumber)) {
                echo "<script>alert('Please enter a valid phone number 11 digits only');
                window.location = 'manageemployee.php';
                </script>";
                exit();
            }

            // Validate name
            if (strlen($name) < 3) {
                echo "<script>alert('Please enter a name with at least 3 characters');
                window.location = 'manageemployee.php';
                </script>";
                exit();
            }

            // Rest of your code for updating
            // ...

            if (empty($name) || empty($phonenumber) || empty($address)) {
                echo "<script>alert('Please Fill out this form');
                window.location = 'manageemployee.php';
                </script>";
            } else {
                $sql = "UPDATE manageemployee SET fullname=?, phonenumber=?, address=? WHERE id=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sssi", $name, $phonenumber, $address, $id);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    $data = "$name";
                    $qrpath = "QRcodesupdate/$name.png";
                    QRcode::png($data, $qrpath);

                    echo "<script>
                            alert('Update employee successfully');
                            window.location = 'manageemployee.php';
                         </script>";
                    exit();
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                    exit();
                }
            }
        }
    } else {
        exit();
    }
} else {
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Employee</title>

    <link rel="stylesheet" href="../css/fontawesome.css">
    <link href="../css/poppins.css" rel="stylesheet">
    <link href="../css/boostrap.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/fontawesome6.css" />
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/manageemployee.css">
    <link rel="icon" href="../images/icon.jpg"> <!-- Stylesheet -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fullnameInput = document.getElementById('fullname');
            const phonenumberInput = document.getElementById('phonenumber');

            fullnameInput.addEventListener('input', function() {
                validateInput(fullnameInput, 5);
            });

            phonenumberInput.addEventListener('input', function() {
                validateInput(phonenumberInput, 11);
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
</head>

<body class="body1">
    <header>
        <a href="manageemployee.php" class="3"><img src="../images/arrow.png" alt=""></a>
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
                <th>Address</th>
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
                    <td><?php echo $row['address'] ?></td>
                    <td>
                        <a href="updateemployee.php?updateemployee=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                        <a href="deleteemployee.php?deleteemployee=<?php echo $row['id']; ?>" class="link-dark"><i class="fa-solid fa-trash fs-5 me-3"></i></a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <form method="post" id="loginform">


        <h3>Register Employee</h3>
        <label for="username">Full Name</label>
        <input type="text" placeholder="Archilles Dela Cruz" name="fullname" id="fullname" autocomplete="off" value="<?php echo $name; ?>" required>

        <label for="username">Phone Number</label>
        <input type="number" placeholder="09xxxxxxxxx" name="phonenumber" id="phonenumber" autocomplete="off" value="<?php echo $phonenumber; ?>" required>

        <label for="username">address</label>
        <input type="text" placeholder="xxxx xxxx 95xx" name="address" id="address" autocomplete="off" value="<?php echo $address; ?>" required>

        <div class="btn">
            <button name="update">Update</button>
        </div>
    </form>
    <script>
        function toggleMenu() {
            document.querySelector('header').classList.toggle('responsive');
        }
    </script>
</body>

</html>