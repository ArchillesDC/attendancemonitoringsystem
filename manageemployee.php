<?php
//DONE
include '../connection.php';
include 'filename.php';



$sql = "SELECT * FROM `manageemployee`";
$result = mysqli_query($conn, $sql);

if (!$result) {
    // Handle the error, log it, or display an error message
    echo "Error: " . mysqli_error($conn);
} else {
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
        <link rel="stylesheet" href="../css/manage.css">
        <link rel="stylesheet" href="../css/manageemployee.css">
        <link rel="icon" href="../images/icon.jpg">
        <!-- Stylesheet -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const fullnameInput = document.getElementById('fullname');
                const phonenumberInput = document.getElementById('phonenumber');
                const usernameInput = document.getElementById('username');
                const passwordInput = document.getElementById('password');

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
            //characters only in fullname
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
            <a href="admin.php" class="3"><img src="../images/arrow.png" alt=""></a>
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
                            <a href="updateemployee.php?updateemployee=<?php echo $row['id']; ?>"><img src="../images/editing.png" alt=""></a>
                            <a href="deleteemployee.php?deleteemployee=<?php echo $row['id']; ?>"><img src="../images/bin.png" alt=""></a>
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
            <input type="text" placeholder="Archilles Dela Cruz" name="fullname" id="fullname" required>

            <label for="username">Phone Number</label>
            <input type="number" placeholder="09xxxxxxxxx" name="phonenumber" id="phonenumber" required>

            <label for="username">address</label>
            <input type="text" placeholder="xxxx xxxx 95xx" name="address" id="address" required>

            <div class="btn">
                <button name="generate">Register</button>
            </div>
        </form>
        <script>
            function toggleMenu() {
                document.querySelector('header').classList.toggle('responsive');
            }
        </script>
    </body>


    </html>
    <?php
    include 'phpqrcode/qrlib.php';

    // Check if form data is submitted
    $existingData = isset($_POST['fname']) ? true : false;

    // Check if the form is submitted for generating QR code
    if (isset($_POST['generate'])) {
        $name = $_POST['fullname'];
        $phonenumber = $_POST['phonenumber'];
        $address = $_POST['address'];

        // Validate form inputs
        if (strlen($name) <= 3) {
            echo "<script>alert('Name must be longer than 3 characters');</script>";
        } else if (preg_match('/[0-9]/', $name)) {
            echo "<script>alert('Name cannot contain numbers');</script>";
        } else if (!preg_match('/^(09|\+63)\d{9}$/', $phonenumber)) {
            echo "<script>alert('Invalid phone number format');</script>";
        } else if (empty($name) || empty($phonenumber) || empty($address)) {
            echo "<script>alert('All fields are required');</script>";
        } else {
            // Insert data into the database
            $sql = "INSERT INTO manageemployee(fullname, phonenumber, address) VALUES ('$name', '$phonenumber', '$address')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $data = "$name";
                $qrpath = "QRcodes/$name.png";

                // Generate QR code
                QRcode::png($data, $qrpath, QR_ECLEVEL_H, 10, 2);

                // Add design to the QR code
                addDesignToQRCode($qrpath, $name);

                // Display success message and redirect
                echo "<script>
                    alert('Employee Registered Successfully');
                    window.location = 'manageemployee.php'; 
                </script>";
            } else {
                // Display error message if database operation fails
                echo "<script>
                    alert('Error: " . $conn->error . "');
                </script>";
            }
        }
    }

    // Check if there is a success message in the session
    if (isset($_SESSION['success_message'])) {
        echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
        unset($_SESSION['success_message']);
    }
    ?>

<?php
}
?>