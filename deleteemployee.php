<?php
include '../connection.php';

// Check if deleteemployee is set in the URL
if (isset($_GET['deleteemployee'])) {
    $id = $_GET['deleteemployee'];

    // Fetching name before deletion
    $getNameQuery = "SELECT fullname FROM manageemployee WHERE id=$id";
    $nameResult = mysqli_query($conn, $getNameQuery);

    if ($nameResult) {
        $row = mysqli_fetch_assoc($nameResult);
        $name = $row['fullname'];

        // Delete employee record
        $deleteEmployeeQuery = "DELETE FROM manageemployee WHERE id=$id";
        $result = mysqli_query($conn, $deleteEmployeeQuery);

        echo "<script>
            // Display loading spinner while processing
            var loading = document.createElement('div');
            loading.innerHTML = 'Deleting employee. Please wait...';
            document.body.appendChild(loading);
        </script>";

        ob_flush();  // Flush the output buffer to send the loading message immediately

        sleep(2);  // Simulate a delay (you can remove this in a real-world scenario)

        if ($result) {
            // Delete QR code file
            $qrCodeFilePath = "QRcodes/{$name}.png"; // Adjust path based on your actual path
            if (file_exists($qrCodeFilePath)) {
                unlink($qrCodeFilePath);

                // Display success message
                echo "<script>
                    alert('Employee {$name} deleted successfully.');
                    window.location = 'manageemployee.php';
                </script>";
            } else {
                // Display error message if unable to delete QR code file
                echo "<script>
                    alert('Error deleting QR code file for {$name}.');
                    window.location = 'manageemployee.php';
                </script>";
            }
        } else {
            // Display error message if deletion from the database fails
            echo "<script>
                alert('Deletion failed: " . mysqli_error($conn) . "');
                window.location = 'manageemployee.php';
            </script>";
        }
    } else {
        // Display error message if unable to fetch employee name
        echo "<script>
            alert('Error fetching employee name: " . mysqli_error($conn) . "');
            window.location = 'manageemployee.php';
        </script>";
    }
}
?>
