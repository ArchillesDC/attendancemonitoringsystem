<?php
//DONE
include '../connection.php';

if (isset($_GET['deleteuser'])) {
    $id = $_GET['deleteuser'];

    $sql = "DELETE FROM manageuser WHERE id=$id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "  <script>
        alert
        alert('User Deleted successfully');
        window.location = 'manageuser.php';
         </script>";
    } else {
        die("Deletion failed: " . mysqli_error($conn));
    }
}

?>

<!--DONE DELETE FUNCTION-->