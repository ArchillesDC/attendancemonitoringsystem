<?php
//DONE
include '../connection.php';

if (isset($_GET['deletescan'])) {
    $id = $_GET['deletescan'];

    $sql = "DELETE FROM scan WHERE id=$id";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "  <script>
        alert('User Deleted successfully');
        window.location = 'attendancetable.php';
         </script>";
    } else {
        die("Deletion failed: " . mysqli_error($conn));
    }
}

?>

<!--DONE DELETE FUNCTION-->