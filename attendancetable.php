<?php
include '../connection.php';

$selectedMonth = isset($_GET['month']) ? $_GET['month'] : '';

$sql = "SELECT * FROM scan";

// Check if a specific month is selected
if ($selectedMonth && $selectedMonth !== "SELECT_MONTH") {
    if (is_numeric($selectedMonth)) {
        // If a numeric value is provided, assume it's a month number
        $sql .= " WHERE MONTH(date) = $selectedMonth";
    } else {
        // If a string value is provided, assume it's a month name
        $monthNumber = date('n', strtotime($selectedMonth)); // Get the month number
        $sql .= " WHERE MONTH(date) = $monthNumber";
    }
}

$sql .= ";";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "Error: " . mysqli_error($conn);
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <style>
            body {
                margin: 0;
                padding: 0;
                background-color: palegreen;
                background-size: cover;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }

            .table-responsive {
                max-height: 400px;
                overflow-y: auto;
            }

            .container {
                max-width: 800px;
                /* Adjust the maximum width as needed */
                margin: 0 auto;
                padding: 20px;
            }

            .btn-dark {
                margin-top: 2px;
                margin-bottom: 10px;
            }

            /* Styles for smaller screens (mobile devices) */
            @media screen and (max-width: 768px) {
                .container {
                    max-width: 100%;
                    /* Use the full width for smaller screens */
                    padding: 10px;
                }

                .btn-dark {
                    width: 100%;
                    /* Make the button full width */
                }
            }

            .btnprnt {
                margin-left: 75rem;
                margin-bottom: 0.5rem;
                width: 6%;
                border-radius: 10px;
                outline: none;
            }

            .btnprnt:hover {
                background-color: lightgreen;
            }

            .srch {
                margin-left: 50rem;
                margin-bottom: 0.5rem;
                width: 25%;
                height: 3rem;
                border-radius: 10px;
                outline: none;
            }
        </style>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ATTENDANCE REPORT</title>

        <!-- Bootstrap CSS -->
        <link href="../css/B2.css" rel="stylesheet">

        <!-- Font Awesome CSS -->
        <link href="../css/b3.css" rel="stylesheet" />

        <link rel="icon" href="images/c2.png" type="">
    </head>

    <body style="background-color: #61A3BA;">
        <div class="container">
            <a href="admin.php">
                <img src="../images/arrow.png" style="width: 40px; margin-right: 30rem;">
            </a>
            <div class="table-responsive">
                <h3>ATTENDANCE</h3>

                <!-- Dropdown for selecting the month -->
                <select id="month" name="month" style="margin-bottom: 10px; font-size: 20px;">
                    <option id="select_month" value="SELECT_MONTH" name="SELECT MONTH">SELECT MONTH</option>
                    <option value="January" name="January">January</option>
                    <option value="February" name="February">February</option>
                    <option value="March" name="March">March</option>
                    <option value="April" name="April">April</option>
                    <option value="May" name="May">May</option>
                    <option value="June" name="June">June</option>
                    <option value="July" name="July">July</option>
                    <option value="August" name="August">August</option>
                    <option value="September" name="September">September</option>
                    <option value="October" name="October">October</option>
                    <option value="November" name="November">November</option>
                    <option value="December" name="December">December</option>
                </select>


                <!-- Add the id attribute to the search input -->
                <input type="search" id="searchInput" placeholder="search here" class="srch">

                <table class="table table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">NAME</th>
                            <th scope="col">DATE</th>
                            <th scope="col">TIME IN</th>
                            <th scope="col">LATE</th>
                            <th scope="col">TIME OUT</th>
                            <th scope="col">OVERTIME</th>
                            <th scope="col">TOTAL HOURS</th>
                            <th scope="col">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?php echo $row['fullname'] ?></td>
                                <td><?php echo $row['date'] ?></td>
                                <td><?php echo $row['timein'] ?></td>
                                <td><?php echo $row['late'] ?></td>
                                <td><?php echo $row['timeout'] ?></td>
                                <td><?php echo $row['overtime'] ?></td>
                                <td><?php echo $row['totalhours'] ?></td>
                                <td><a href="deletetable.php?deletescan=<?php echo $row['id']; ?>"><img src="../images/bin.png" alt=""></a></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Add the print button -->
                <button class="btnprnt">
                    <img src="../images/printing.png" alt="">
                </button>

                <!-- Bootstrap JavaScript -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var dropdown = document.getElementById('month');
                        var selectedMonth = '<?php echo $selectedMonth; ?>';

                        // Set the initial value of the dropdown to the selected month if present
                        if (selectedMonth) {
                            dropdown.value = selectedMonth;
                        }

                        // Update the displayed month name in the dropdown
                        dropdown.addEventListener('change', function() {
                            var selectedMonth = this.value;
                            var selectedMonthName = this.options[this.selectedIndex].text;

                            // Update the displayed month name in the dropdown
                            this.options[0].text = selectedMonthName; // Assuming the first option is "SELECT MONTH"

                            // Redirect based on the selected month
                            if (selectedMonth !== "SELECT_MONTH") {
                                window.location.href = 'attendancetable.php?month=' + encodeURIComponent(selectedMonth);
                            } else {
                                window.location.href = 'attendancetable.php'; // Redirect without month parameter
                            }
                        });

                        // Add a click event listener to the print button
                        var printButton = document.querySelector('.btnprnt');
                        printButton.addEventListener('click', function() {
                            // Get the table content
                            var table = document.querySelector('.table');

                            // Check if the table has any rows
                            if (table.rows.length <= 1) { // Assuming the first row is the table header
                                alert('No data in the table.');
                                return;
                            }

                            // Create a new window for printing
                            var printWindow = window.open('', '_blank');

                            // Clone the table
                            var clonedTable = table.cloneNode(true);

                            // Style the cloned table for printing
                            clonedTable.style.borderCollapse = 'collapse';
                            clonedTable.style.width = '100%';

                            // Apply styles to each cell
                            for (var i = 0; i < clonedTable.rows.length; i++) {
                                for (var j = 0; j < clonedTable.rows[i].cells.length; j++) {
                                    clonedTable.rows[i].cells[j].style.border = '3px solid #000'; // 3px black border
                                    clonedTable.rows[i].cells[j].style.padding = '10px'; // 10px padding
                                    clonedTable.rows[i].cells[j].style.textAlign = 'center'; // Center text
                                }
                            }

                            // Remove the "ACTION" column from the cloned table
                            for (var i = 0; i < clonedTable.rows.length; i++) {
                                clonedTable.rows[i].deleteCell(-1); // Remove the last cell (ACTION column)
                            }

                            // Write only the styled cloned table content to the new window
                            printWindow.document.open();
                            printWindow.document.write('<html><head><title>Attendance Report</title></head><body>');
                            printWindow.document.write(clonedTable.outerHTML); // Copy the table HTML
                            printWindow.document.write('</body></html>');
                            printWindow.document.close();

                            // Print the content
                            printWindow.print();
                        });

                        // Add a keyup event listener to the search input
                        var searchInput = document.getElementById('searchInput');
                        searchInput.addEventListener('keyup', function() {
                            var searchText = this.value.toLowerCase();
                            var tableRows = document.querySelectorAll('.table tbody tr');

                            tableRows.forEach(function(row) {
                                var name = row.cells[0].textContent.toLowerCase(); // Assuming "NAME" is the first column
                                if (name.includes(searchText)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </body>

    </html>
<?php
}
?>